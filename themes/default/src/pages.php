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

    "/about/{id}" => [
        'view' => 'views/about',
        'layout' => 'layouts/base',
        'data' => [
            'user' => 'Plugins\\Demonicious\\Test::about'
        ]
    ],

    "/users" => [
        'view' => 'views/user',

        "pages" => [
            "data" => "Plugins\\Demonicious\\Test::data"
        ]
    ]
];