<?php

namespace app\controllers;

use app\models\Country;

class LocationsController extends \yii\web\Controller
{
    public function beforeAction($action) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        parent::beforeAction($action);
    }

}
