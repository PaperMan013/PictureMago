<?php

namespace app\controllers;

use app\rules\Rule;
use app\services\DefaultService;
use Yii;
use yii\base\Exception;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class DefaultController extends \yii\rest\Controller
{
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
     * @param string $filename
     * @param string $version
     * @return string
     */
    public function actionIndex(string $filename, string $version): string
    {
        return $filename . ' + ' . $version . ' + ' . Yii::getAlias('@webroot');
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

        $folder = DefaultService::getOriginalFolder();

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
        $folder = DefaultService::getOriginalFolder();

        if (!file_exists(Yii::getAlias('@webroot') . "/{$folder}/{$filename}")) {
            throw new NotFoundHttpException();
        }

        $folders = array_map(fn(Rule $rule) => $rule->name, Yii::$app->mago->rules);

        foreach ($folders as $folder) {
            $path = Yii::getAlias('@webroot') . "/{$folder}/{$filename}";
            if (file_exists($path)) {
                unlink($path);
            }
        }

        return 1;
    }
}
