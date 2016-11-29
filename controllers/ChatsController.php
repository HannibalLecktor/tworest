<?php

namespace app\controllers;

use app\models\District;
use app\models\security\LoginForm;
use app\models\security\SignupForm;
use app\models\security\User;
use app\models\Smile;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class ChatsController extends Controller
{
    public $background;

    public function actionShow($id) {
        $chat = District::findOne($id);
        $usersOb = User::find()
            ->innerJoin('messages', 'users.id = messages.user_id AND messages.district_id = ' . intval($id))
            ->with([
                'district' => function($q) {
                    $q->with([
                        'city' => function($q) {
                            $q->with('country');
                        }
                    ]);
                }
            ])
            ->indexBy('id')
            ->distinct()
            ->all();

        $users = array_map(function ($val) {
            return $val->toArray();
        }, $usersOb);

        foreach ($users as $key => &$user) {
            if ($district = $usersOb[$key]->district) {
                $user['district'] = $district->name;
                $user['city'] = $district->city->name;
                $user['country'] = $district->city->country->name;
            }
        }
        unset($user);

        $breadcrumb = $this->getBreadcrumb($id);
        //$background = $this->background;
        $background = '';
        $smiles = array_map(function ($val) {
            return $val->getImageUrl('image');
        }, Smile::find()->indexBy('sym')->all());

        $data = [
            'chat'       => $chat,
            'smiles'     => $smiles,
            'users'      => $users,
            'breadcrumb' => $breadcrumb,
            'background' => $background
        ];

        if(\Yii::$app->user->isGuest) {
            $data['loginForm'] = new LoginForm;
            $data['regForm'] = new SignupForm;

        }

        return $this->render('show', $data);
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
            $this->background = $model->city->image;

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
                ]
            ];
        } else {
            return [];
        }
    }

}
