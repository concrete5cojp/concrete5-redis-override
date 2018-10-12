<?php
use Application\Redis\Driver\Redis;

$redisDriver = [
    'core_filesystem' => [
        'class' => Redis::class,
        'options' => [
            'servers' => [
                [
                    'server' => '<redis_server>',
                    'port' => 6379,
                    'ttl' => 30 //Connection Timeout - not TTL for objects
                ],
            ],
            'prefix'=>'c5_cache',
            'database'=>0 // Use different Redis Databases - optional
        ],
    ],
];

$cache = [];
$session = [];
if (Redis::isAvailable()) {
    $cache = [
        'page' => [
            'adapter' => 'redis',
            'redis' => [
                'servers' => [
                    [
                        'server' => '<redis_server>',
                        'port' => 6379,
                        'ttl' => 30 //Connection Timeout - not TTL for objects
                    ],
                ],
                'prefix'=>'c5_cache',
                'database'=>1 // Use different Redis Databases - optional
            ],
        ],
        'levels' => [
            'overrides' => [
                'drivers' => $redisDriver,
            ],
            'expensive' => [
                'drivers' => $redisDriver,
            ],
            'object' => [
                'drivers' => $redisDriver,
            ],
        ],
    ];
    $session = [
        'handler' => 'redis',
        'redis' => [
            'servers' => [
                [
                    'server' => '<redis_server>',
                    'port' => 6379,
                    'ttl' => 30 //Connection Timeout - not TTL for objects
                ],
            ],
            'prefix' => 'c5_session',
            'database'=>2 // Use different Redis Databases - optional
        ],
    ];
}
return [
    'cache' => $cache,
    'session' => $session
];