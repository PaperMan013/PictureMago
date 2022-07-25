<?php

namespace app\processors;

use Imagine\Image\ImageInterface;
use Imagine\Image\ManipulatorInterface;
use yii\base\Model;
use yii\web\HttpException;

/**
 * Абстрактная модель обработчика изображения
 */
abstract class Processor extends Model
{
    /** @var string Идентификатор обработчика */
    public $id;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'string'],
        ];
    }

    /**
     * @inheritdoc
     * @throws HttpException
     */
    public function init()
    {
        parent::init();

        if (!$this->validate()) {
            throw new HttpException(500, implode('; ', $this->getErrorSummary(true)));
        }
    }

    /**
     * Выполнение обработки изображения
     * @param ImageInterface $image
     * @return ImageInterface|ManipulatorInterface
     */
    abstract public function execute(ImageInterface $image);
}
