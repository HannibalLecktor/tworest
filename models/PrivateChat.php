<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "private_chats".
 *
 * @property integer $id
 * @property integer $owner_id
 * @property integer $guest_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class PrivateChat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'private_chats';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['owner_id'], 'required'],
            [['owner_id', 'guest_id', 'created_at', 'updated_at'], 'integer']
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'         => 'ID',
            'owner_id'   => 'Owner ID',
            'guest_id'   => 'Guest ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
