<?php
return [
    'test.dev' => [
        'layout'   => 'layout/test.dev',
    ],
    'view_manager' => [
        'template_map' => [
            'layout/teste.local' => __DIR__.'/view/layout/layout.phtml',
        ],
    ]
];
