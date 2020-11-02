<?php

return [
    'theme' => [
        'name' => 'Default Theme',
        'shortcode' => 'ig-default-theme'
    ],

    'requires' => [
        'Plugins\\Demonicious\\OrderTracker'
    ],

    'settings' => [
        'gtag_id' => [
            'type' => 'string',
            'default' => null
        ]
    ]
];