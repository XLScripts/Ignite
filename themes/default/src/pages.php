<?php 

return [
    "/" => [
        "title"  => "Homepage",
        "view"   => "views/home",
        "layout" => "layouts/base",
        "vars" => [
            "hello" => "hi"
        ]
    ],

    "/test" => "Plugins\\Demonicious\\Test::data",
    "/test2" => [
        "method"  => "POST",
        "handler" => "Plugins\\Demonicious\\Test::data"
    ],
    "/test3" => [
        "title"  => "Test Page",
        "view"   => "views/home",
        "layout" => "layouts/base",
        "vars" => [
            "hello" => "hi"
        ],
        "data" => "Plugins\\Demonicious\\Test::data"
    ],

    "/users" => [
        "type" => "group",
        "routes" => [
            "/"    => [
                'view' => 'views/user'
            ],
            "data" => "Plugins\\Demonicious\\Test::data"
        ]
    ]
];