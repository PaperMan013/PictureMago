<?php

namespace app\processors;

use Imagine\Image\ImageInterface;
use Imagine\Image\ManipulatorInterface;
use yii\web\HttpException;

/**
 * Модель масштабирования изображения
 */
class Resize extends Processor
{
    /** @var int Максимальная длина стороны */
    public $maxSide;

    /** @var int Максимальная ширина */
    public $maxWidth;

    /** @var int Максимальная высота */
    public $maxHeight;

    /** @var bool Разрешено увеличение */
    public $allowEnlarge = false;


    public function rules()
    {
        return array_merge(parent::rules(), [
            [['maxSide', 'maxWidth', 'maxHeight'], 'integer'],
            ['allowEnlarge', 'boolean'],
        ]);
    }

    /**
     * @param ImageInterface $image
     * @return ManipulatorInterface|ImageInterface
     * @throws HttpException
     */
    public function execute(ImageInterface $image)
    {
        switch (true) {
            case isset($this->maxSide):
                return $this->resizeSide($image, $this->maxSide);
            case isset($this->maxWidth):
                return $this->resizeWidth($image, $this->maxWidth);
            case isset($this->maxHeight):
                return $this->resizeHeight($image, $this->maxHeight);
            default:
                throw new HttpException(500, "Необходимо указать один из параметров обработчика {$this->id}");
        }
    }

    /**
     * @param ImageInterface $image
     * @param int $size
     * @return ImageInterface|ManipulatorInterface
     */
    private function resizeSide(ImageInterface $image, int $size)
    {
        if ($image->getSize()->getHeight() > $image->getSize()->getWidth()) {
            return $this->resizeHeight($image, $size);
        }

        return $this->resizeWidth($image, $size);
    }

    /**
     * @param ImageInterface $image
     * @param int $size
     * @return ImageInterface|ManipulatorInterface
     */
    private function resizeHeight(ImageInterface $image, int $size)
    {
        if ($image->getSize()->getHeight() < $size && !$this->allowEnlarge) {
            return $image;
        }
        return $image->resize($image->getSize()->heighten($size));
    }

    /**
     * @param ImageInterface $image
     * @param int $size
     * @return ImageInterface|ManipulatorInterface
     */
    private function resizeWidth(ImageInterface $image, int $size)
    {
        if ($image->getSize()->getWidth() < $size && !$this->allowEnlarge) {
            return $image;
        }
        return $image->resize($image->getSize()->widen($size));
    }
}
