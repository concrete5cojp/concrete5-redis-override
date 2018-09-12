<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Redis\Session;


/**
 * Redis based session storage handler based on the Redis class
 * provided by the PHP redis extension.
 *
 * @author Dalibor Karlović <dalibor@flexolabs.io>
 * modified by Derek Cameron <derek@concrete5.co.jp> for concrete5
 */
class RedisSessionHandler implements \SessionHandlerInterface, \SessionUpdateTimestampHandlerInterface
{

    private $redis;

    /**
     * @var string Key prefix for shared environments
     */
    private $prefix;

    /**
     * List of available options:
     *  * prefix: The prefix to use for the keys in order to avoid collision on the Redis server.
     *
     * @param \Redis|\RedisArray|\RedisCluster $redis
     * @param array                                           $options An associative array of options
     *
     * @throws \InvalidArgumentException When unsupported client or options are passed
     */
    public function __construct($redis, array $options = array())
    {
        if (!$redis instanceof \Redis && !$redis instanceof \RedisArray && !$redis instanceof \RedisCluster) {
            throw new \InvalidArgumentException(sprintf('%s() expects parameter 1 to be Redis, RedisArray, RedisCluster, %s given', __METHOD__, is_object($redis) ? get_class($redis) : gettype($redis)));
        }

        if ($diff = array_diff(array_keys($options), array('prefix'))) {
            throw new \InvalidArgumentException(sprintf('The following options are not supported "%s"', implode(', ', $diff)));
        }

        $this->redis = $redis;
        $this->prefix = $options['prefix'] ?? 'sf_s';
    }

    /**
     * @param string $sessionId
     *
     * @return string
     */
    protected function doRead($sessionId): string
    {
        return $this->redis->get($this->prefix.$sessionId) ?: '';
    }

    /**
     * @param string $sessionId
     * @param string $data
     *
     * @return bool
     */
    protected function doWrite($sessionId, $data): bool
    {
        $result = $this->redis->setEx($this->prefix.$sessionId, (int) ini_get('session.gc_maxlifetime'), $data);

        return $result;
    }

    /**
     * @param string $sessionId
     *
     * @return bool
     */
    protected function doDestroy($sessionId): bool
    {
        $this->redis->del($this->prefix.$sessionId);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function gc($maxlifetime): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function updateTimestamp($sessionId, $data)
    {
        return $this->redis->expire($this->prefix.$sessionId, (int) ini_get('session.gc_maxlifetime'));
    }
    private $sessionName;
    private $prefetchId;
    private $prefetchData;
    private $newSessionId;
    private $igbinaryEmptyData;

    /**
     * {@inheritdoc}
     */
    public function open($savePath, $sessionName)
    {
        $this->sessionName = $sessionName;
        if (!headers_sent() && !ini_get('session.cache_limiter') && '0' !== ini_get('session.cache_limiter')) {
            header(sprintf('Cache-Control: max-age=%d, private, must-revalidate', 60 * (int) ini_get('session.cache_expire')));
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function validateId($sessionId)
    {
        $this->prefetchData = $this->read($sessionId);
        $this->prefetchId = $sessionId;

        return '' !== $this->prefetchData;
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        if (null !== $this->prefetchId) {
            $prefetchId = $this->prefetchId;
            $prefetchData = $this->prefetchData;
            $this->prefetchId = $this->prefetchData = null;

            if ($prefetchId === $sessionId || '' === $prefetchData) {
                $this->newSessionId = '' === $prefetchData ? $sessionId : null;

                return $prefetchData;
            }
        }

        $data = $this->doRead($sessionId);
        $this->newSessionId = '' === $data ? $sessionId : null;

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        if (null === $this->igbinaryEmptyData) {
            // see https://github.com/igbinary/igbinary/issues/146
            $this->igbinaryEmptyData = \function_exists('igbinary_serialize') ? igbinary_serialize(array()) : '';
        }
        if ('' === $data || $this->igbinaryEmptyData === $data) {
            return $this->destroy($sessionId);
        }
        $this->newSessionId = null;

        return $this->doWrite($sessionId, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        if (!headers_sent() && ini_get('session.use_cookies')) {
            if (!$this->sessionName) {
                throw new \LogicException(sprintf('Session name cannot be empty, did you forget to call "parent::open()" in "%s"?.', get_class($this)));
            }
            $sessionCookie = sprintf(' %s=', urlencode($this->sessionName));
            $sessionCookieWithId = sprintf('%s%s;', $sessionCookie, urlencode($sessionId));
            $sessionCookieFound = false;
            $otherCookies = array();
            foreach (headers_list() as $h) {
                if (0 !== stripos($h, 'Set-Cookie:')) {
                    continue;
                }
                if (11 === strpos($h, $sessionCookie, 11)) {
                    $sessionCookieFound = true;

                    if (11 !== strpos($h, $sessionCookieWithId, 11)) {
                        $otherCookies[] = $h;
                    }
                } else {
                    $otherCookies[] = $h;
                }
            }
            if ($sessionCookieFound) {
                header_remove('Set-Cookie');
                foreach ($otherCookies as $h) {
                    header($h, false);
                }
            } else {
                setcookie($this->sessionName, '', 0, ini_get('session.cookie_path'), ini_get('session.cookie_domain'), ini_get('session.cookie_secure'), ini_get('session.cookie_httponly'));
            }
        }

        return $this->newSessionId === $sessionId || $this->doDestroy($sessionId);
    }
}