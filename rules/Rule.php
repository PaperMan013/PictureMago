<?php

namespace app\rules;

use app\exceptions\ValidateErrorException;
use yii\base\Model;

abstract class Rule extends Model
{
    public string $name;


    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'string'],
        ];
    }

    public function init()
    {
        parent::init();

        if (!$this->validate()) {
            throw new ValidateErrorException($this->getErrorSummary(true));
        }
    }

    abstract public function execute(): bool;
}
