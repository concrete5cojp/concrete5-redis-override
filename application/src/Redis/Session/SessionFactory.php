<?php
namespace Application\Redis\Session;

use Concrete\Core\Application\Application;
use Concrete\Core\Http\Request;
use Concrete\Core\Session\SessionFactoryInterface;
use Illuminate\Config\Repository;
use Concrete\Core\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Session as SymfonySession;

/**
 * Class SessionFactory
 * Base concrete5 session factory. Modified to use redis
 */
class SessionFactory implements SessionFactoryInterface
{
    /** @var \Concrete\Core\Application\Application */
    private $app;

    /** @var \Concrete\Core\Http\Request */
    private $request;

    public function __construct(Application $app, Request $request)
    {
        $this->app = $app;
        $this->request = $request;
    }

    /**
     * Create a new symfony session object
     * This method MUST NOT start the session.
     *
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    public function createSession()
    {
        $config = $this->app['config'];
        $storage = $this->getSessionStorage($config);

        $session = new SymfonySession($storage);
        $session->setName($config->get('concrete.session.name'));

        /*
         * @todo Move this to somewhere else
         */
        $this->request->setSession($session);

        return $session;
    }

    /**
     * @param \Illuminate\Config\Repository $config
     *
     * @return \Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface
     */
    private function getSessionStorage(Repository $config)
    {
        $app = $this->app;

        if ($app->isRunThroughCommandLineInterface()) {
            $storage = new MockArraySessionStorage();
        } else {
            $handler = $this->getSessionHandler($config);
            $storage = new NativeSessionStorage(array(), $handler);

            // Initialize the storage with some options
            $options = $config->get('concrete.session.cookie');
            if ($options['cookie_path'] === false) {
                $options['cookie_path'] = $app['app_relative_path'] . '/';
            }

            $lifetime = $config->get('concrete.session.max_lifetime');
            $options['gc_maxlifetime'] = $lifetime;
            $storage->setOptions($options);
        }

        return $storage;
    }

    /**
     * @param \Illuminate\Config\Repository $config
     *
     * @return \SessionHandlerInterface
     */
    private function getSessionHandler(Repository $config)
    {
        if ($config->get('concrete.session.handler') == 'redis' && class_exists('Redis')) {
            $options = $config->get('concrete.session.redis');
            $servers = [];
            if (!empty($options['servers'])) $servers = $options['servers'];


            if (is_array($servers) && count($servers) == 1 ) {

                $server = $servers[0];
                $redis = new \Redis();

                if (isset($server['socket']) && $server['socket']) {
                    $redis->connect($server['socket']);
                } else {
                    $port = isset($server['port']) ? $server['port'] : 6379;
                    $ttl = isset($server['ttl']) ? $server['ttl'] : 0.5;
                    $redis->connect($server['server'], $port, $ttl);
                }

                // auth - just password
                if (isset($server['password'])) {
                    $redis->auth($server['password']);
                }
            } else {
                if (!empty($servers) && is_array($servers)) {
                    foreach ($servers as $server) {
                        $ttl = '0.5';
                        if (isset($server['ttl'])) {
                            $ttl = $server['ttl'];
                        } elseif (isset($server[2])) {
                            $ttl = $server[2];
                        }

                        if (isset($server['socket'])) {
                            $servers[] = array('socket' => $server['socket'], 'ttl' => $ttl);
                        } else {
                            $host = '127.0.0.1';
                            if (isset($server['server'])) {
                                $host = $server['server'];
                            } elseif (isset($server[0])) {
                                $host = $server[0];
                            }

                            $port = '6379';
                            if (isset($server['port'])) {
                                $port = $server['port'];
                            } elseif (isset($server[1])) {
                                $port = $server[1];
                            }

                            $servers[] = array('server' => $host, 'port' => $port, 'ttl' => $ttl);
                        }
                    }
                } else {
                    $servers = array(array('server' => '127.0.0.1', 'port' => '6379', 'ttl' => 0.5));
                }

                    $serverArray = array();
                    foreach ($servers as $server) {
                        $serverString = $server['server'];
                        if (isset($server['port'])) {
                            $serverString .= ':' . $server['port'];
                        }

                        $serverArray[] = $serverString;
                    }
                    $redis = new \RedisArray($serverArray, []);
                }

                if (!empty($options['database'])) {
                    $redis->select((int) $options['database']);
                }

                if (!empty($options['prefix'])) {
                    $options = ['prefix'=>$options['prefix']];
                } else {
                    $options = [];
                }

            $handler = new RedisSessionHandler($redis, $options);
        } else {
            $savePath = $config->get('concrete.session.save_path') ?: null;
            $handler = new NativeFileSessionHandler($savePath);
        }

        return $handler;
    }
}
