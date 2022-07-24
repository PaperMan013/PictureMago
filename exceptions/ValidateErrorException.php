<?php

namespace app\exceptions;

use yii\web\HttpException;

class ValidateErrorException extends HttpException
{
    public function __construct(array $errors, $previous = null)
    {
        parent::__construct(500, implode('; ', $errors), 0, $previous);
    }
}
