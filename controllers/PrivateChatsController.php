<?php

namespace app\controllers;

use app\models\PrivateChat;
use app\models\security\User;
use app\models\Smile;
use Yii;
use yii\web\ForbiddenHttpException;


class PrivateChatsController extends BaseController
{
    public function actionShow($id) {
        $chat = PrivateChat::findOne($id);
        if(!in_array(Yii::$app->user->identity->getId(), [$chat['owner_id'], $chat['guest_id']])) {
            throw new ForbiddenHttpException('access forbidden');
        }
        $users = User::find()
            ->innerJoin('messages', 'users.id = messages.user_id AND messages.private_chat_id = '.intval($id))
            ->distinct()
            ->indexBy('id')
            ->all();
        $users = array_map(function($val) {
            return $val->toArray();
        }, $users);
        $smiles = array_map(function($val) {
            return $val->getImageUrl('image');
        }, Smile::find()->indexBy('sym')->all());

        return $this->render('show', compact('chat', 'smiles', 'users'));
    }
}
