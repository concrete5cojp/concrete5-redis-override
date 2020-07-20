<?php
/* ******************************
/application/config/site.php

If you want to create as a separate environment, rename this file from site.php to ENV.site.php (Replace `ENV` with the name of environment, then modify environment detect function to match the ENV name.

Make sure to check all other settings on /concrete/config/site.php to make sure that you didn't forget any settings to be saved as a file and load them properly on both load-balanced instance.

- SEO, Sitename
- FavIcons
- CKEditor Settings
- Multilingual
****************************** */
    
return [
    'sites' => [
        'default' => [
            /* ******************************
            SEO Settings: Site name, URL, Tracking Code
            ****************************** */
            // Set site name
            // 'name' => 'SITE NAME',

            /*
            'seo' => [
                // Set canonical URL
                'canonical_url' => 'https://example.com/',
                'canonical_url_alternative' => '',
                'canonical_tag' => [
                    'enabled' => true,
                ],
                // Set tracking code
                'tracking' => [
                    'code' => [
                        'header' => '',
                        'footer' => '',
                    ],
                ],
            ],
            */
            /* ******************************
            FavIcon Settings
            ****************************** */
            /*
            'misc' => [
                // File ID for favicon
                'favicon_fid' => null,
                // File ID for iPhone home screen icon
                'iphone_home_screen_thumbnail_fid' => null,
                // File ID for Windows 8 tile icon
                'modern_tile_thumbnail_fid' => null,
                // Background color for Windows 8 tile icon
                'modern_tile_thumbnail_bgcolor' => null,
                // theme-color meta-tag (eg color of toolbar for Chrome 39+ on Android)
                'browser_toolbar_color' => null,
            ],
            */
        ],
    ],
];
