<?php

namespace app\models;

use app\models\security\User;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "messages".
 *
 * @property integer $id
 * @property string $text
 * @property integer $district_id
 * @property integer $private_chat_id
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'messages';
    }

    public function fields() {
        return [
            'id',
            'created_at',
            'text',
            'district_id',
            'user_id',
            'private_chat_id',
            'image'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getImage() {
        return $this->hasOne(MessageImage::className(), ['message_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['text', 'user_id'], 'required'],
            [['text'], 'string'],
            [['district_id', 'private_chat_id', 'user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'          => 'ID',
            'text'        => 'Text',
            'district_id' => 'District ID',
            'user_id'     => 'User ID',
            'created_at'  => 'Created At',
            'updated_at'  => 'Updated At',
        ];
    }

    public function behaviors() {
        return [
            'class' => TimestampBehavior::className(),
        ];
    }
}
