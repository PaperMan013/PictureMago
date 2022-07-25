<?php

use app\Mago;
use app\processors\Resize;

return [
    'class' => Mago::class,
    'processors' => [
        [
            'class' => Resize::class,
            'id' => 'side500',
            'maxSide' => 500,
        ],
        [
            'class' => Resize::class,
            'id' => 'width500',
            'maxWidth' => 500,
        ],
        [
            'class' => Resize::class,
            'id' => 'height500',
            'maxHeight' => 500,
        ],
    ],
    'versions' => [
        's1' => 'side500',
        's2' => 'width500',
        's3' => 'height500',
    ],
];
