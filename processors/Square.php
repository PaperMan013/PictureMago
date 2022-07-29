<?php

namespace app\processors;

use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use Imagine\Imagick\Imagine;

/**
 * Модель приведения изображения к квадрату
 */
class Square extends Processor
{
    /** @var int[] Цвет добавляемого фона в формате массива RGB */
    public $backgroundColor = [255, 255, 255];


    /**
     * @inheritDoc
     */
    public function execute(ImageInterface $image)
    {
        if ($image->getSize()->getWidth() === $image->getSize()->getHeight()) {
            return $image;
        }

        $square = (new Imagine())->create($this->getSquareSize($image), new Color($this->backgroundColor));

        $square->paste($image, $this->getStartPoint($square, $image));

        return $square;
    }

    /**
     * Вычисляет сторону необходимого квадрата
     * @param ImageInterface $image
     * @return Box
     */
    private function getSquareSize(ImageInterface $image): Box
    {
        $side = $image->getSize()->getWidth();
        if ($image->getSize()->getHeight() > $side) {
            $side = $image->getSize()->getHeight();
        }
        return new Box($side, $side);
    }

    /**
     * Вычисляет начальную точку для вставки по центру меньшего изображения в большее
     * @param ImageInterface $parentImage
     * @param ImageInterface $childImage
     * @return Point
     */
    private function getStartPoint(ImageInterface $parentImage, ImageInterface $childImage): Point
    {
        $x = round(($parentImage->getSize()->getWidth() - $childImage->getSize()->getWidth()) / 2);
        $y = round(($parentImage->getSize()->getHeight() - $childImage->getSize()->getHeight()) / 2);

        return new Point($x, $y);
    }
}
