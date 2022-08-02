<?php

namespace app\controllers;

use app\forms\AddForm;
use app\forms\DeleteForm;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SiteController extends \yii\rest\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'bearerAuth' => [
                'class' => HttpBearerAuth::class,
                'except' => (YII_DEBUG ?? false) === true? ['*'] : ['index'],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index'  => ['GET'],
                    'add'   => ['POST'],
                    'delete' => ['POST'],
                    'flush' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        Yii::info(
            print_r(Yii::$app->request->headers->toArray(), 1) . PHP_EOL . print_r(Yii::$app->request->post(), 1),
            'Http request'
        );
        return parent::beforeAction($action);
    }

    /**
     * Создаёт версию изображения
     * @param string $version
     * @param string $filename
     * @return Response
     * @throws BadRequestHttpException
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function actionIndex(string $version, string $filename): Response
    {
        if ($version === Yii::$app->mago->originalVersion) {
            throw new NotFoundHttpException();
        }

        if (!in_array($version, array_keys(Yii::$app->mago->versions))) {
            throw new BadRequestHttpException("Версия {$version} не предусмотрена");
        }

        Yii::$app->mago->createVariant($version, $filename);

        return $this->redirect("/{$version}/{$filename}");
    }

    /**
     * Добавляет изображение
     * @return int
     * @throws BadRequestHttpException
     */
    public function actionAdd()
    {
        return (new AddForm())->add();
    }

    /**
     * Удаляет изображение и все его версии
     * @param string $filename
     * @return int
     * @throws NotFoundHttpException
     */
    public function actionDelete(): int
    {
        return (new DeleteForm())->delete();
    }

    /**
     * Удаляет все созданные версии всех изображений
     * @return int
     */
    public function actionFlush(): int
    {
        foreach (Yii::$app->mago->versions as $version => $processors) {
            $this->rmDir(Yii::getAlias('@webroot') . "/{$version}");
        }

        return 1;
    }

    /**
     * Удаляет папку вместе с содержимым
     * @param string $dir
     */
    private function rmDir(string $dir)
    {
        if (file_exists($dir)) {
            foreach (scandir($dir) as $item) {
                if (in_array($item, ['.', '..'])) {
                    continue;
                }

                $path = "{$dir}/{$item}";

                if (is_dir($path)) {
                    $this->rmDir($path);
                } else {
                    unlink($path);
                }
            }

            rmdir($dir);
        }
    }
}
