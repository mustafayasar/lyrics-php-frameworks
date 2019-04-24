<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ''                                  => 'site/index',
                'singers/<i>/<page:\d+>'            => 'site/singers',
                'singers/<i>'                       => 'site/singers',
                'songs/<i>/<page:\d+>'              => 'site/songs',
                'songs/<i>'                         => 'site/songs',
                '<singer_slug>-songs/<page:\d+>'    => 'site/singer-songs',
                '<singer_slug>-songs'               => 'site/singer-songs',
                '<singer_slug>/<song_slug>-lyrics'  => 'site/song-view',
                'random-lyrics'                     => 'site/random-song-view',
                'search'                            => 'site/search',
            ],
        ],
    ],
    'params' => $params,
];
