<?php

use app\Mago;
use app\User;
use yii\log\FileTarget;

return [
    'id' => 'picture-mago',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\controllers',
    'defaultRoute' => null,
    'aliases' => [
        '@app' => dirname(__DIR__),
    ],
    'bootstrap' => ['log'],
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
        'log' => [
            'flushInterval' => 1,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    //'enabled' => YII_DEBUG ?? false,
                    'levels' => ['warning'],
                    'logFile' => '@app/logs/warnings.log'
                ],
                [
                    'class' => FileTarget::class,
                    //'enabled' => YII_DEBUG ?? false,
                    'levels' => ['error'],
                    'logFile' => '@app/logs/errors.log'
                ],
                [
                    'class' => FileTarget::class,
                    //'enabled' => YII_DEBUG ?? false,
                    'levels' => ['trace'],
                    'logFile' => '@app/logs/debug.log'
                ],
                [
                    'class' => FileTarget::class,
                    //'enabled' => YII_DEBUG ?? false,
                    'levels' => ['info'],
                    'categories' => ['Http request'],
                    'logFile' => '@app/logs/http_requests.log',
                    'logVars' => [],
                ]
            ],
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
