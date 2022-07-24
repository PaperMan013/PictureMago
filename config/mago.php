<?php

return [
    'class' => '\app\components\Mago',
    'rules' => [
        '\app\rules\Original',
        [
            'class' => '\app\rules\Scale',
            'name' => 'preview',
            'maxWidth' => 500,
            'maxHeight' => 500,
        ]
    ],
    'versions' => [
        'original',
        'preview' => 'original'
    ],
];
