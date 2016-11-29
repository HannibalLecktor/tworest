<?php

namespace app\controllers;

use app\models\MessageImage;
use yii\helpers\Json;

class MessageImagesController extends \yii\web\Controller
{
    public $layout = false;

    public function actionUpload() {
        $model = new MessageImage;
        try {
            if($model->load(\Yii::$app->request->post()) && $model->save()) {
                return $this->renderContent(Json::encode([
                    "id"           => $model->id,
                    "name"         => $model->image,
                    "size"         => filesize(\Yii::getAlias('@webroot') . $model->getImageUrl('image')),
                    "url"          => $model->getImageUrl('image'),
                    "thumbnailUrl" => $model->getImageUrl('image', 'mini'),
                ]));
            } else {
                \Yii::info($model->errors);
                $errorMessage = join("<br>", $model->getErrors());
            }
        } catch(\Exception $e) {
            \Yii::info($e->getMessage());
            $errorMessage = $e->getMessage();
        }

        \Yii::$app->response->statusCode = 422;

        return $this->renderContent($errorMessage);
    }
}
