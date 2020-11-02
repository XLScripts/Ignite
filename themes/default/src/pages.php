<?php

return [
    "/" => [
        'title'  => 'Home',
        'view'   => 'views/home',
        'layout' => 'layouts/base',
        'data' => [
            'todos' => 'Plugins\\Demonicious\\OrderTracker::data'
        ]
    ],

    "/about" => [
        'title'   => 'About',
        'view'    => 'views/about',
        'layouts' => 'layouts/base',
        'data' => [
            'widget' => 'Plugins\\Demonicious\\OrderTracker::widget'
        ]
    ]
];