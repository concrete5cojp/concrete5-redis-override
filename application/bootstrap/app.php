<?php

// MUST BE PLACED AT THE TOP OF APP.PHP before a session is created!
if (\Application\Redis\Driver\Redis::isAvailable()) {
    $app->bind('Concrete\Core\Session\SessionFactoryInterface', 'Application\Redis\Session\SessionFactory');
    $this->app->singleton('session', function ($app) {
        return $app->make('Application\Redis\Session\SessionFactory')->createSession();
    });
}