<?php

return [
    'permissions' => [
        env('SHOPIFY_PERMISSIONS')
    ],
    'host' => env('APP_HOST'),
    "cdn_base_script_tag"        => env("CDN_BASE_SCRIPT_TAG", "")
];
