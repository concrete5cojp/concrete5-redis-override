<?php
namespace Application\Redis\Session;

use Application\Redis\Driver\Redis;
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
            if (isset($options['prefix'])) {
                $config = ['prefix'=> $options['prefix']];
                unset($config['prefix']);
            } else {
                $config = [];
            }
            $redis = new Redis($options);

            $handler = new RedisSessionHandler($redis, $config);
        } else {
            $savePath = $config->get('concrete.session.save_path') ?: null;
            $handler = new NativeFileSessionHandler($savePath);
        }

        return $handler;
    }
}
