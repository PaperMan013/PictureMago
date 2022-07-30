<?php

namespace app\forms;

use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;

class AddForm extends Model
{
    /** @var UploadedFile Файл изображения */
    public $image;

    /** @var bool Признак замены изображения если существует */
    public $replace = false;


    public function rules()
    {
        return [
            ['image', 'image', 'skipOnEmpty' => false, 'extensions' => ['jpg', 'png', 'gif', 'webp']],
            ['replace', 'boolean']
        ];
    }

    public function init()
    {
        parent::init();

        $this->image = UploadedFile::getInstanceByName('image');
        $this->load(Yii::$app->request->post(), '');

        if (!$this->validate()) {
            throw new BadRequestHttpException(implode('; ', $this->getErrorSummary(true)));
        }
    }

    /**
     * @return int
     * @throws BadRequestHttpException
     */
    public function add(): int
    {
        $path = Yii::getAlias('@webroot') . '/' . Yii::$app->mago->originalVersion;

        if (file_exists("{$path}/{$this->image->name}")) {
            if ($this->replace) {
                foreach (Yii::$app->mago->versions as $version => $processors) {
                    if (file_exists(Yii::getAlias('@webroot') . "/{$version}/{$this->image->name}")) {
                        unlink(Yii::getAlias('@webroot') . "/{$version}/{$this->image->name}");
                    }
                }
            } else {
                throw new BadRequestHttpException("{$this->image->name} already exists");
            }
        }

        if (!file_exists($path)) {
            mkdir($path, 0775);
        }

        return (int)move_uploaded_file($this->image->tempName, "{$path}/{$this->image->name}");
    }
}
