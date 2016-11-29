<?php

namespace app\controllers;

use app\models\District;
use app\models\security\ChangePasswordForm;
use app\models\security\User;
use Imagine\Exception\RuntimeException;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\Url;

class PersonalController extends BaseController
{
    public $defaultAction = 'update';

    public function actionUpdate() {
        if(Url::previous() != Url::current()) {
            #dump(Url::previous(), Url::current());
            Url::remember(Url::previous(), 'backurl');
        }
        $model = new ChangePasswordForm;
        $user = $this->loadUser();
        $user->scenario = User::SCENARIO_PROFILE;

        if($user->load(Yii::$app->request->post())) {
            try {
                $user->save();

                if(Yii::$app->request->isAjax) {
                    return $this->renderContent('Изменения сохранены');
                }

                return $this->redirect(['update']);
            } catch(RuntimeException $e) {
                $user->addError('avatar', 'Ошибка при загрузке файла');
            }
        }

        $breadcrumbs = [];
        if ($user->district_id) {
            $breadcrumbs = $this->getBreadcrumb($user->district_id);
        }

        //dump($breadcrumbs);
        //die();

        return $this->render('update', compact('user', 'model', 'breadcrumbs'));
    }

    public function actionChangePassword() {
        $model = new ChangePasswordForm;
        $user = $this->loadUser();
        $user->scenario = User::SCENARIO_PROFILE;

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
        } else {
            return $this->render('update', compact('user', 'model'));
        }
    }

    protected function loadUser() {
        return User::findOne(Yii::$app->user->identity->getId());
    }

    public function getBreadcrumb($id) {
        $model = District::find()
            ->with([
                'city' => function (ActiveQuery $q) {
                    $q->with('country');
                }
            ])
            ->where(['id' => $id])->one();

        if($model !== null) {

            return [
                [
                    'label' => $model->city->country->name,
                    'url'   => ['/search/?country=' . $model->city->country->id],
                ],
                [
                    'label' => $model->city->name,
                    'url'   => ['/search/?country=' . $model->city->country->id . '&city=' . $model->city->id],
                ],
                [
                    'label' => $model->name,
                    'url'   => ['/chats/' . $model->id],
                ]
            ];
        } else {
            return [];
        }
    }
}
