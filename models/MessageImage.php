<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "message_images".
 *
 * @property integer $id
 * @property string $image
 * @property integer $message_id
 */
class MessageImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'message_images';
    }

    public function getMessage() {
        return $this->hasOne(Message::className(), ['id' => 'message_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['image'], 'required']
        ];
    }

    public function fields() {
        return [
            'id',
            'message_id',
            'image' => function($model) {
                return $model->getImageUrl('image', 'mini');
            },
            'bigImage' => function($model) {
                return $model->getImageUrl('image');
            }
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'         => 'ID',
            'image'      => 'Image',
            'message_id' => 'Message ID',
        ];
    }

    public function behaviors() {
        return [
            [
                'class'         => \maxmirazh33\image\Behavior::className(),
                'savePathAlias' => 'img/upload/messages/',
                'urlPrefix'     => '/img/upload/messages/',
                'crop'          => false,
                'attributes'    => [
                    'image' => [
                        'width'      => 640,
                        'height'     => 480,
                        'thumbnails' => [
                            'mini' => [
                                'width'  => 120,
                                'height' => 120
                            ],
                        ],
                    ],
                ],
            ],
            BlameableBehavior::className(),
        ];
    }
}
