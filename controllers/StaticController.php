<?php
/**
 * Created by PhpStorm.
 * User: Khadeev Fanis
 * Date: 10/10/15
 * Time: 21:50
 */
namespace app\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Page;

class StaticController extends Controller
{
    public function actionIndex($action) {

        $page = Page::find()
            ->select('pages.name, pages.text')
            ->innerJoinWith([
                'language' => function ($query) {
                    $query->onCondition(['languages.code' => \Yii::$app->language]);
                }])
            ->where([
                'pages.code' => $action,
            ])
            ->one();

        if (!$page)
            throw new NotFoundHttpException;
        else
            return $this->render('@app/views/pages/static', ['content'=>$page]);
    }
}