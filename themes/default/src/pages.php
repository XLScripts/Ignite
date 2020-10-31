<?php 

return [
    "/" => [
        "title"   => "Welcome",
        "view"    => "views/home.htm",
        "layout"  => "base.htm",
    ],

    "/about/{id}" => [
        'title' => 'About Us',
        'view'  => 'views/about.htm',
        'dynamic' => true,
        'data' => [
            'users' => 'App\\Demonicious\\TestPlugin::data',
            'todos' => 'App\\Demonicious\\TestPlugin::todos'
        ]
    ]
];