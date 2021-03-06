<?php

namespace app\controllers\admin;

use app\models\Country;
use Yii;
use app\models\City;
use app\models\CitiesSearch;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\controllers\AdminBaseController;
use app\models\UploadForm;

/**
 * AdminCityController implements the CRUD actions for Cities model.
 */
class CitiesController extends AdminBaseController
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

    /**
     * Lists all Cities models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new CitiesSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cities model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Cities model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new City;

        $fileUpload = new UploadForm();

        if($model->load(Yii::$app->request->post())) {

            $fileUpload->getImagesPath($model, 'image');

            if ($model->hasErrors()) {
                $countries = $this->loadCountries();
                return $this->render('update', compact('model', 'countries'));
            }
            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

        $countries = $this->loadCountries();
        return $this->render('update', compact('model', 'countries'));
    }

    /**
     * Updates an existing Cities model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        $fileUpload = new UploadForm();

        if($model->load(Yii::$app->request->post())) {
            $fileUpload->getImagesPath($model, 'image');

            dump($model);

            if ($model->hasErrors()) {
                $countries = $this->loadCountries();
                return $this->render('update', compact('model', 'countries'));
            }
            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

        $countries = $this->loadCountries();
        return $this->render('update', compact('model', 'countries'));
    }

    /**
     * Deletes an existing Cities model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Cities model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return City the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if(($model = City::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function loadCountries() {
        $countries = Country::find()->select(['id', 'name'])->asArray()->all();

        return ArrayHelper::map($countries, 'id', 'name');
    }
}
