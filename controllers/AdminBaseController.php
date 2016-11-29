<?php
/**
 * Created by PhpStorm.
 * User: Khadeev Fanis
 * Date: 08/10/15
 * Time: 23:13
 */

namespace app\controllers;

use yii\filters\VerbFilter;

class AdminBaseController  extends \yii\web\Controller
{
    public $layout = 'admin';

    public function behaviors() {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!\Yii::$app->user->identity->is_admin)
            return $this->redirect('/');

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true; // or false to not run the action
    }
}