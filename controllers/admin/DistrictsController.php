<?php

namespace app\controllers\admin;

use app\models\City;
use Yii;
use app\models\District;
use app\models\DistrictsSearch;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use app\models\UploadForm;
use app\controllers\AdminBaseController;

/**
 * AdminDistrictController implements the CRUD actions for Districts model.
 */
class DistrictsController extends AdminBaseController
{
    /**
     * Lists all Districts models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new DistrictsSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
    /**
     * Displays a single Districts model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Districts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new District;
        $fileUpload = new UploadForm();

        if($model->load(Yii::$app->request->post())) {
            $fileUpload->getImagesPath($model, 'image');
            if ($model->hasErrors()) {
                $cities = $this->loadCities();
                return $this->render('create', compact('model', 'cities'));
            }

            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

        $cities = $this->loadCities();
        return $this->render('create', compact('model', 'cities'));
    }

    /**
     * Updates an existing Districts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $fileUpload = new UploadForm();
        if($model->load(Yii::$app->request->post())) {
            $fileUpload->getImagesPath($model, 'image');

            //dump($model);

            if ($model->hasErrors()) {
                $cities = $this->loadCities();
                return $this->render('update', compact('model', 'cities'));
            }

            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

        $cities = $this->loadCities();
        return $this->render('update', compact('model', 'cities'));
    }

    /**
     * Deletes an existing Districts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Districts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return District the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if(($model = District::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function loadCities() {
        $cities = City::find()->select(['id', 'name'])->asArray()->all();

        return ArrayHelper::map($cities, 'id', 'name');
    }
}
