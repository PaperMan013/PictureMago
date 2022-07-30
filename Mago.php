<?php

namespace app;

use app\processors\Processor;
use Imagine\Image\ImageInterface;
use Imagine\Imagick\Imagine;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\HttpException;

/**
 *
 */
class Mago extends \yii\base\Component
{
    /** @var string Имя папки для хранения оригиналов */
    public $originalVersion = 'original';

    /** @var string Наименование элемента формы для отправки файла изображения */
    public $inputName = 'image';

    /** @var string Секретный ключ для добавления и удаления изображений */
    public $token = 'secret';

    /** @var Processor[]|array Правила обработки изображений */
    public $processors = [];

    /** @var array Версии изображений */
    public $versions = [];

    /**
     * Инициализация компонента
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        foreach ($this->processors as $i => $config) {
            $this->processors[$i] = Yii::createObject($config);
        }
    }

    /**
     * @param string $version
     * @param string $filename
     * @throws HttpException
     */
    public function createVariant(string $version, string $filename)
    {
        $ids = self::getProcessorsIds($version);
        $image = self::getImage($filename);

        foreach ($ids as $id) {
            $image = self::getProcessor($id)->execute($image);
        }

        self::save($version, $filename, $image);
    }

    /**
     * @param string $filename
     * @return ImageInterface
     */
    private static function getImage(string $filename): ImageInterface
    {
        return (new Imagine())
            ->open(Yii::getAlias('@webroot') . '/' . Yii::$app->mago->originalVersion . '/' . $filename);
    }

    /**
     * Возвращает список обработчиков для версии
     * @param string $version
     * @return array
     * @throws HttpException
     */
    private static function getProcessorsIds(string $version): array
    {
        $ids = Yii::$app->mago->versions[$version];
        switch (gettype($ids)) {
            case 'array':
                return $ids;
            case 'string':
                return [$ids];
            default:
                throw new HttpException(
                    500,
                    'Список идентификаторов обработчиков должен быть строкой или массивом строк'
                );
        }
    }

    /**
     * Возвращает обработчик по идентификатору
     * @param string $id
     * @return Processor
     * @throws HttpException
     */
    private static function getProcessor(string $id): Processor
    {
        foreach (Yii::$app->mago->processors as $processor) {
            if ($processor->id === $id) {
                return $processor;
            }
        }

        throw new HttpException(500, "Обработчик {$id} не найден");
    }

    private static function save(string $version, string $filename, ImageInterface $image)
    {
        $dir = Yii::getAlias('@webroot') . '/' . $version;

        if (!file_exists($dir)) {
            mkdir($dir);
        }

        $image->save("{$dir}/{$filename}", ['jpeg_quality' => 95, 'webp_quality' => 95]);
    }
}
