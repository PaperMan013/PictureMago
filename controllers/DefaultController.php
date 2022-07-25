<?php

namespace app\controllers;

use app\processors\Processor;
use Yii;
use yii\base\Exception;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class DefaultController extends \yii\rest\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'bearerAuth' => [
                'class' => HttpBearerAuth::class,
                'except' => ['index']
            ],
        ];
    }

    /**
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
     * @param string $filename
     * @return int
     * @throws BadRequestHttpException
     * @throws Exception
     */
    public function actionSave(string $filename): int
    {
        if (!$file = UploadedFile::getInstanceByName(Yii::$app->mago->inputName)) {
            throw new BadRequestHttpException();
        }

        $folder = Yii::$app->mago->originalVersion;

        if (file_exists(Yii::getAlias('@webroot') . "/{$folder}/{$filename}")) {
            throw new HttpException(400, "{$filename} already exists");
        }

        if (!file_exists(Yii::getAlias('@webroot') . "/{$folder}")) {
            mkdir(Yii::getAlias('@webroot') . "/{$folder}", 0775);
        }

        return (int)move_uploaded_file($file->tempName, Yii::getAlias('@webroot') . "/{$folder}/{$filename}");
    }

    /**
     * @param string $filename
     * @return int
     * @throws NotFoundHttpException
     */
    public function actionDelete(string $filename): int
    {
        $folder = Yii::$app->mago->originalVersion;

        if (!file_exists(Yii::getAlias('@webroot') . "/{$folder}/{$filename}")) {
            throw new NotFoundHttpException();
        }

        $folders = array_map(
            function (Processor $processor) {
                return $processor->id;
            },
            Yii::$app->mago->processors
        );

        foreach ($folders as $folder) {
            $path = Yii::getAlias('@webroot') . "/{$folder}/{$filename}";
            if (file_exists($path)) {
                unlink($path);
            }
        }

        return 1;
    }
}
