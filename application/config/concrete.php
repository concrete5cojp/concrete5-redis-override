$redisSettings = [
            'servers' => [
                [
                    'server' => '<redisAdress>',
                    'port' => 6379
                ]
            ],
            'session_prefix' => 'c5session',
            'prefix'=>'c5application'
        ];
$redisDriver = [
    'core_filesystem'=>[
        'class' => \Application\Redis\Driver\Redis::class,
        'options' => $redisSettings
        ]
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
        ]
    ],
    'session' => [
        'handler'=> 'redis',
        'redis' =>$redisSettings
    ],
];