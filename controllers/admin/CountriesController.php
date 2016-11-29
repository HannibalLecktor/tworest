<?php

namespace app\controllers\admin;

use app\controllers\AdminBaseController;
use app\models\District;
use app\models\DistrictsSearch;
use app\models\UploadForm;
use Yii;
use app\models\Country;
use app\models\CountrySearch;
use yii\web\NotFoundHttpException;

/**
 * AdminCountryController implements the CRUD actions for Country model.
 */
class CountriesController extends AdminBaseController
{
    /**
     * Lists all Country models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new CountrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Country model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Country model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Country();
        $fileUpload = new UploadForm();

        if($model->load(Yii::$app->request->post())) {

            $fileUpload->getImagesPath($model, 'flag');
            $fileUpload->getImagesPath($model, 'capital_image');

            if ($model->hasErrors()) {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Upload files
     */

    /**
     * Updates an existing Country model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $fileUpload = new UploadForm();

        if($model->load(Yii::$app->request->post())) {

            $fileUpload->getImagesPath($model, 'flag');
            $fileUpload->getImagesPath($model, 'capital_image');


            if ($model->hasErrors()) {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Country model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Country model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Country the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if(($model = Country::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
