<?php

namespace app;

use Yii;
use yii\base\Model;

class User extends Model implements \yii\web\IdentityInterface
{
    /** @var int */
    public $id;

    /** @var string Секретный ключ */
    public $token;


    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->id = 1;
        $this->token = Yii::$app->mago->token ?? '';
    }

    /**
     * @inheritDoc
     */
    public static function findIdentity($id)
    {
        return new self();
    }

    /**
     * @inheritDoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $model = new self();

        if (!empty($token) && $token === $model->token) {
            return $model;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($authKey)
    {
        return null;
    }
}
