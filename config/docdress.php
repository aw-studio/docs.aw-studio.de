<?php

return [
    'path' => resource_path('docs'),

    'repos' => [
        'litstack/litstack' => [
            'route_prefix'    => 'https://litstack.io/docs',
            'title'           => 'Litstack',
            'composer'        => 'litstack/litstack',
            'description'     => 'Laravel Admin-Panel framework',
            'default_version' => 0,
        ],
        'aw-studio/docdress' => [
            'route_prefix'    => 'https://github.com/aw-studio/docdress#readme',
            'title'           => 'Docdress',
            'composer'        => 'aw-studio/docdress',
            'description'     => 'Deploy markdown documentations',
            'default_version' => 0,
        ],
        'aw-studio/laravel-migrations-merger' => [
            'route_prefix'    => 'https://github.com/aw-studio/laravel-migrations-merger#readme',
            'title'           => 'Laravel Migrations Merger',
            'composer'        => 'aw-studio/laravel-migrations-merger',
            'description'     => 'Merge laravel migrations',
            'default_version' => 0,
        ],
        'cbl/blade-script' => [
            'route_prefix'    => 'https://github.com/cbl/blade-script#readme',
            'title'           => 'Blade Script',
            'composer'        => 'cbl/blade-script',
            'description'     => 'Transpile & minify scripts in Blade',
            'default_version' => 0,
        ],
        'cbl/blade-style' => [
            'route_prefix'    => 'https://github.com/cbl/blade-style#readme',
            'title'           => 'Blade Style',
            'composer'        => 'cbl/blade-style',
            'description'     => 'Compile & minify styles in Blade',
            'default_version' => 0,
        ],
        'aw-studio/docs' => [
            'title'           => 'AW-Studio Docs',
            'description'     => 'Company Documentation',
            'route_prefix'    => 'docs',
            'subfolder'       => null,
            'default_page'    => 'workflow/development',
            'default_version' => 'master',
            'access_token'    => env('GITHUB_ACCESS_TOKEN_AW_STUDIO', null),
            'webhook_token'   => env('GITHUB_CI_SECRET', null),
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
