<?php namespace app\components;

use yii\db\Query;
use yii\web\DbSession;

class CustomSession extends DbSession
{
    protected function composeFields($id, $data) {
        $fields = parent::composeFields($id, $data);
        $fields['user_id'] = \Yii::$app->user->id;

        return $fields;
    }
}