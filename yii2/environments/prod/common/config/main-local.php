<?php
return [
    'components' => [
        'db' => [
            'class'     => 'yii\db\Connection',
            'dsn'       => 'mysql:host=localhost;dbname=lyrics',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8mb4',
            'enableSchemaCache'     => true,
            'schemaCacheDuration'   => 2*60*60,
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname'  => 'localhost',
                'port'      => 6379,
                'database'  => 1,
            ],
        ],
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => '127.0.0.1:9200'],
                // configure more hosts if you have a cluster
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
