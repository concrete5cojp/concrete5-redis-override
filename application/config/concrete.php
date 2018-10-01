<?php
$redisSettings = [
            'servers' => [
                [
                    'server' => '<redisAdress>',
                    'port' => 6379,
                    'ttl'=> 0.5 //Time for connection to server to timeout
                ]
            ],
            'session_prefix' => md5(DIR_APPLICATION),
            'prefix'=>md5(DIR_BASE)
        ];
$redisDriver = [
    'core_filesystem'=>[
        'class' => \Application\Redis\Driver\Redis::class,
        'options' => $redisSettings
        ]
    ];



return ['cache' => [
        'page' => [
            'adapter' => 'redis',
            'redis' =>$redisSettings
            ]
        ],
        'levels' => [
            'overrides' => [
                'drivers' => $redisDriver
            ],
            'expensive' => [
                'drivers' => $redisDriver
            ],
            'object' => [
                'drivers' => $redisDriver
            ],
        ],
    'session' => [
        'handler'=> 'redis',
        'redis' =>$redisSettings
    ],
];