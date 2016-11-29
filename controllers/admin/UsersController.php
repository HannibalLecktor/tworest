<?php

namespace app\controllers\admin;

use app\controllers\AdminBaseController;
use app\models\District;
use app\models\security\ChangePasswordForm;
use app\models\security\User;
use app\models\UsersSearch;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;


/**
 * AdminUserController implements the CRUD actions for Users model.
 */
class UsersController extends AdminBaseController
{
    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsersSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /** @var User $model */
        $model = $this->findModel($id);

        if($model->load(Yii::$app->request->post())) {
            if(($newPassword = Yii::$app->request->post('newpassword'))) {
                $model->setPassword($newPassword);
            }
            if($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function loadDistricts() {
        $districts = District::find()->select(['id', 'name'])->asArray()->all();

        return ArrayHelper::map($districts, 'id', 'name');
    }

    public function actionChangePassword() {
        $model = new ChangePasswordForm;
        $user = $this->loadUser();

        if($model->load(Yii::$app->request->post())) {
            if($model->validate()) {
                try {
                    $user->setPassword($_POST['ChangePasswordForm']['newpass']);
                    if($user->save()) {
                        Yii::$app->getSession()->setFlash(
                            'success', 'Password changed'
                        );

                        return $this->redirect(['update']);
                    } else {
                        Yii::$app->getSession()->setFlash(
                            'error', 'Password not changed'
                        );

                        return $this->redirect(['personal/update']);
                    }
                } catch(\Exception $e) {
                    Yii::$app->getSession()->setFlash(
                        'error', "{$e->getMessage()}"
                    );

                    return $this->render('update', compact('user', 'model'));
                }
            } else {
                return $this->render('update', compact('user', 'model'));
            }
        }
    }
}
