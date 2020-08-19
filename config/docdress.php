<?php

return [
    'path' => resource_path('docs'),

    'repos' => [
        'aw-studio/docs' => [
            'route_prefix'    => 'docs',
            'subfolder'       => null,
            'default_page'    => 'workflow/development',
            'default_version' => 'master',
            'access_token'    => env('GITHUB_ACCESS_TOKEN_AW_STUDIO', null),
            'versions'        => [
                'master' => 'Master',
            ],
        ],
    ],

    'themes' => [
        'default' => [
            'primary' => '#4d60ca',

            'code-bg'            => '#f5f8fb',
            'code-selection'     => '#b3d4fc',
            'code-value'         => '#055472',
            'code-prop'          => '#d44545',
            'code-function'      => '#4d60ca',
            'code-variable'      => '#588bbd',
            'code-string'        => '#169f0c',
            'code-default-color' => '#090910',
        ],
        'fjord' => [
            'primary' => '#4951f2',

            'code-bg'            => '#f5f8fb',
            'code-selection'     => '#b3d4fc',
            'code-value'         => '#055472',
            'code-prop'          => '#d44545',
            'code-function'      => '#4951f2',
            'code-variable'      => '#588bbd',
            'code-string'        => '#169f0c',
            'code-default-color' => '#090910',
        ],
    ],
];
