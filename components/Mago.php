<?php

namespace app\components;

use app\rules\Rule;
use Yii;
use yii\base\InvalidConfigException;

class Mago extends \yii\base\Component
{
    /** @var Rule[]|array */
    public array $rules = [];
    public array $versions = [];
    public string $inputName = 'image';
    public string $token = 'secret';

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        foreach ($this->rules as $i => $config) {
            $this->rules[$i] = Yii::createObject($config);
        }
    }
}
