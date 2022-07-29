<?php

use app\Mago;
use app\User;

return [
    'id' => 'picture-mago',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\controllers',
    'aliases' => [
        '@app' => dirname(__DIR__),
    ],
    'components' => [
        'request' => [
            'enableCookieValidation' => false
        ],
        'user' => [
            'identityClass' => User::class,
            'enableSession' => false,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'GET /<version>/<filename>' => '/default/index',
                'POST /<filename>' => '/default/save',
                'DELETE /<filename>' => '/default/delete',
            ],
        ],
        'mago' => [
            'class' => Mago::class,
        ],
    ],
];
