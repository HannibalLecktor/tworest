<?php

namespace app\controllers;

use app\models\Country;
use app\models\FeedbackForm;
use app\models\PagesSearch;
use Yii;
use \yii\web\Controller;
use yii\db\ActiveQuery;
use vova07\imperavi\actions\GetAction;
use app\models\District;
use app\models\Page;

class PagesController extends Controller
{
    public function actionHome() {
        $locations = Country::find()
            ->innerJoinWith([
                'cities' => function (ActiveQuery $q) {
                    $q->innerJoinWith('districts');
                }
            ])->indexBy('id')
            ->all();

        $locations = array_map(function($val) {
            return $val->toArray();
        }, $locations);

        $districtCount = District::find()->count();
        $countryCount = Country::find()->count();

        return $this->render('home', compact('locations', 'districtCount', 'countryCount', 'staticPages'));
    }

    public function actionError() {
        return $this->renderContent('error');
    }

    public function actionImagesGet() {
        print_r($this->getImagesJson());
    }

    public function actionAdmin() {
        if(is_object(\Yii::$app->user->identity) && \Yii::$app->user->identity->is_admin) {
            return $this->redirect('/admin/countries');
        } else {
            return $this->redirect('/');
        }
    }

    public function actions() {
        return [
            'images-get'   => [
                'class' => 'vova07\imperavi\actions\GetAction',
                'url'   => '/img/', // URL адрес папки где хранятся изображения.
                'path'  => $_SERVER['DOCUMENT_ROOT'] . "/img", // Или абсолютный путь к папке с изображениями.
                'type'  => GetAction::TYPE_IMAGES,
            ],
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadAction',
                'url'   => '/img/', // URL адрес папки где хранятся изображения.
                'path'  => $_SERVER['DOCUMENT_ROOT'] . "/img", // Или абсолютный путь к папке с изображениями.
            ]
        ];

    }

    public function actionFeedback() {
        $model = new FeedbackForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $successMessage = Yii::t('app', 'Thank you for message!');
            if ($user = Yii::$app->user)
                $id = $user->id;
            else
                $id = '';

            Yii::$app->mailer->compose()
                ->setFrom(\Yii::$app->params['noreplayEmail'])
                ->setTo(\Yii::$app->params['adminEmail'])
                ->setSubject('сообщение из формы обратной связи')
                ->setHtmlBody('
                    <p>Вам сообщение из формы обратной связи</p>
                    <p>Отправитель:</p><p>' . $model->name . '</p>
                    <p>Email:</p><p>' . $model->email . '</p>
                    <p>Email:</p><p>' . $model->text . '</p>
                    <p>Id пользователя:</p><p>' . $id . '</p>'
                )
                ->send();

            return $this->render('feedback', compact('model', 'successMessage'));
        } else {
            return $this->render('feedback', ['model' => $model]);
        }
    }
}