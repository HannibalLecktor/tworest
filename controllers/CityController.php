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
class CityController extends Controller
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
     * Lists all Country models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => City::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Country model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $districtCount = DistrictsSearch::getCountByCity($id);
        $districtList = District::find()->select(['name', 'id', 'image'])->where(['city_id' => $id])->all();
        $districtList = ArrayHelper::toArray($districtList);

        return $this->render('@app/views/country/index', [
            'country' => $model->country->name,
            'city' => $model->name,
            'district' => '',
            'model' => $model,
            'districtCount' => $districtCount,
            'list' => $districtList,
            'link' => '/district/',
            'linkName' => \Yii::t('app', 'Go'),
            //'background' => $model->image
            'background' => ''
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
        if (($model = City::find()->with('country')->where(['id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
