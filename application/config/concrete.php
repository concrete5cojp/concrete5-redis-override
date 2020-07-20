<?php
/* ******************************
/application/config/concrete.php

If you want to create as a separate environment, rename this file from concrete.php to ENV.concrete.php (Replace `ENV` with the name of environment, then modify environment detect function to match the ENV name.

Make sure to check all other settings on /concrete/config/site.php to make sure that you didn't forget any settings to be saved as a file and load them properly on both load-balanced instance.

- Trusted Proxy
- Cache
- Session
- Debug
- System Email

- Allowed File Extensions
- CSV Format
- Email SMTP Setting
- User Registration
- Gravatar
- Design
- Queue
- Logging
- Marketplace
- Misc
- White Labeling
- Username, Password
- Permission
    - Forward to login
- Statistics
- Editor & Composer
and a lot more
****************************** */


/* ******************************
Proxy Setting - Make sure to uncomment below
****************************** */
// Proxy Server's IP if it's sitting behind ELB
$proxyIps = [
    // '10.0.1.0/24', // AWS VPC 1a
    // '10.0.2.0/24', // AWS VPC 1c
];

/* ******************************
Redis Main Setting
****************************** */
// Proxy Server's IP if it's sitting behind ELB
$redisDriver = [
    'preferred_driver' => 'redis',
    'drivers'=> [
        'redis'=>[
            'options' => [
                'servers' => [
                    [
                        'server' => '<redis_server>',
                        'port' => 6379,
                        'ttl' => 30 //Connection Timeout - not TTL for objects
                    ],
                ],
                /* CHANGE PREFIX to yout site */
                'prefix'=>'c5_cache',
                'database'=> 1
            ],
        ],
    ],
];

$cache = [];
$session = [];

/* ******************************
Cache Setting with Redis
****************************** */
$cache = [
    'page' => [
        'adapter' => 'redis',
        'redis' => [
            'servers' => [
                [
                    // ENTER YOUR REDIS SERVER INFO HERE
                    'server' => '<redis_server>',
                    'port' => 6379,
                    'ttl' => 30 //Connection Timeout - not TTL for objects
                ],
            ],
            'prefix'=>'c5_cache',
            'database'=> 2
        ],
    ],
    'levels' => [
        'overrides' => $redisDriver,
        'expensive' => $redisDriver,
        'object' => ['preferred_driver' => 'core_ephemeral'],
    ],
    /* ******************************
    Cachel Setting for the site uses Redis - If it's load balancing, generated override settings won't be saved UNLESS you use Redis stored setting which was introduced in 8.5.3.
    ****************************** */
    /*
    'blocks' => true,
    'assets' => false,
    'theme_css' => false,
    'overrides' => true,
    'pages' => 'blocks',
    'full_page_lifetime' => 'default',
    'full_page_lifetime_value' => null,
    'clear' => [
        'thumbnails' => false,
    ],
    */
];

$session = [
    // If you change the session name other than CONCRETE5, the cokkie name will be changed.
    'name' => 'CONCRETE5',
    'handler' => 'redis',
    'redis' => [
        'servers' => [
            [
                // ENTER YOUR REDIS SERVER INFO HERE
                'server' => '<redis_server>',
                'port' => 6379,
                'ttl' => 30 //Connection Timeout - not TTL for objects
            ],
        ],
        // Rename the session prefix so that you could co-host mutiple sites in one ElastiCache
        'prefix' => 'c5_session',
        'database'=>2 // Use different Redis Databases - optional
    ],
];

return [
    'cache' => $cache,
    'session' => $session,
    /* ******************************
    Proxy Setting - Make sure to uncomment below. If it's load balancing, generated override settings won't be saved UNLESS you use Redis stored setting which was introduced in 8.5.3.
    ****************************** */
    /* 
    'security' => [
        'trusted_proxies' => [
            'ips' => $proxyIps,
        ]
    ],
    */
    /* ******************************
    Debug Setting - Prod Site may not want to display the error message.     If it's load balancing, generated override settings won't be saved UNLESS you use Redis stored setting which was introduced in 8.5.3.
    ****************************** */
    /* 
    'debug' => [
        'display_errors' => false,
        'detail' => 'message',
        'error_reporting' => null,
    ],
    */
    /* ******************************
    System Email From Settings - If it's load balancing, generated override settings won't be saved UNLESS you use Redis stored setting which was introduced in 8.5.3.
    ****************************** */
    /* 
    'email' => [
        'default' => [
            'name' => 'NAME',
            'address' => 'email@example.com',
        ],
        'forgot_password' => [
            'name' => 'NAME',
            'address' => 'email@example.com',
        ],
        'form_block' => [
            'address' => 'email@example.com',
        ],
        'register_notification' => [
            'address' => 'email@example.com',
        ],
        'validate_registration' => [
            'name' => 'NAME',
            'address' => 'email@example.com',
        ],
        'workflow_notification' => [
            'name' => 'NAME',
            'address' => 'email@example.com',
        ],
    ],
    'spam' => [
        'notify_email' => 'email@example.com',
    ],
    */
    
];