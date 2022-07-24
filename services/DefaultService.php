<?php

namespace app\services;

use app\rules\Original;

class DefaultService extends \yii\base\BaseObject
{
    /**
     * @return string|null
     */
    public static function getOriginalFolder(): ?string
    {
        foreach (\Yii::$app->mago->rules as $rule) {
            if ($rule instanceof Original) {
                return $rule->name;
            }
        }

        return null;
    }
}
