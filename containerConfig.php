<?php
use function DI\create;
use function DI\get;

return [
    '\\Twig\\Loader\\FilesystemLoader' => new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates/src'),

    '\\Twig\\Environment' => create()
        ->constructor(get('\\Twig\\Loader\\FilesystemLoader'), [
            'cache' => realpath(__DIR__ . '/templates/cache'),
        ]),

    'mysqli' => $GLOBALS['db'], // todo fix this shit,
    'App\\DatabaseInterface' => get('mysqli'),

    '\\App\\HitCounter' => create(),
];
