<?php

use app\Mago;
use app\processors\Resize;

return [
    'class' => Mago::class,
    'token' => 'secret',
    'processors' => [
        [
            'class' => Resize::class,
            'id' => 'side500',
            'maxSide' => 500,
        ],
    ],
    'versions' => [
        'preview' => ['side500'],
    ],
];
