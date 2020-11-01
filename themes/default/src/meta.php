<?php

return [
    "theme" => [
        "name" => "Ignition-CMS Theme",
        "shortcode" => "default",
        "version" => "1.0.0",
        "author" => [
            "name" => "Demonicious",
            "url" => "https://github.com/demonicious"
        ]
    ],

    "requires" => [
        "Plugins\\Demonicious\\Test"
    ],

    "settings" => [
        "title" => [
            "type" => "string",
            "default" => "Ignition-CMS Website"
        ],
        "gtag_id" => [
            "type" => "string",
            "default" => null
        ]
    ]
];