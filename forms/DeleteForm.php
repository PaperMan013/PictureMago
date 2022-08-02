<?php

namespace app\forms;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class DeleteForm extends \yii\base\Model
{
    /** @var string Имя файла изображения */
    public $filename;


    public function rules()
    {
        return [
            ['filename', 'required'],
            ['filename', 'string'],
        ];
    }

    public function init()
    {
        parent::init();

	$this->load(Yii::$app->request->post(), '');

        if (!$this->validate()) {
            throw new BadRequestHttpException(implode('; ', $this->getErrorSummary(true)));
        }
    }

    /**
     * @return int
     * @throws NotFoundHttpException
     */
    public function delete(): int
    {
        if (!file_exists(Yii::getAlias('@webroot') . '/' . Yii::$app->mago->originalVersion . "/{$this->filename}")) {
            throw new NotFoundHttpException("Файл {$this->filename} не найден");
        }

        foreach (Yii::$app->mago->versions as $version => $processors) {
            $path = Yii::getAlias('@webroot') . "/{$version}/{$this->filename}";
            if (file_exists($path)) {
                unlink($path);
            }
        }

        return 1;
    }
}
