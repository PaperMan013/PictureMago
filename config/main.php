<?php

use app\Mago;
use app\User;

return [
    'id' => 'picture-mago',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\controllers',
    'defaultRoute' => null,
    'aliases' => [
        '@app' => dirname(__DIR__),
    ],
    'components' => [
        'request' => [
            'enableCookieValidation' => false,
            'enableCsrfCookie' => false,
            'enableCsrfValidation' => false,
        ],
        'user' => [
            'identityClass' => User::class,
            'enableSession' => false,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/site/index' => '/site/not-found',
                'POST /<action>' => '/site/<action>',
                'GET /<version>/<filename>' => '/site/index',
            ],
        ],
        'mago' => [
            'class' => Mago::class,
        ],
    ],
];
