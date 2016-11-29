<?php

namespace app\controllers;

use app\models\City;
use app\models\District;
use Yii;
use app\models\Country;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\DistrictsSearch;

/**
 * CountryController implements the CRUD actions for Country model.
 */
class DistrictController extends Controller
{
    public $layout = 'main';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Displays a single Country model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $districtList[] = ArrayHelper::toArray($model, ['id', 'name', 'image']);

        return $this->render('@app/views/country/index', [
            'country' => $model->city->country->name,
            'city' => $model->city->name,
            'district' => $model->name,
            'model' => $model,
            'districtCount' => '',
            'list' => $districtList,
            'link' => '/chats/',
            'linkName' => \Yii::t('app', 'Sign In'),
            'background' => ''
            //'background' => $model->city->image
        ]);
    }

    /**
     * Finds the Country model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Country the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = District::find()
                ->with([
                    'city' => function($q) {
                        $q->with('country');
                    }
                ])
                ->where(['id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
