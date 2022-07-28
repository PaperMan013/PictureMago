<?php

// закомментируйте следующие две строки при использовании в рабочем режиме
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require dirname(__DIR__) . '/config/main.php';
(new yii\web\Application($config))->run();