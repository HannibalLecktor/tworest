<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "smiles".
 *
 * @property integer $id
 * @property string $code
 * @property string $sym
 * @property string $image
 */
class Smile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'smiles';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['sym', 'image'], 'required'],
            [['sym'], 'string', 'max' => 8],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'   => 'ID',
            'code' => 'Code',
            'sym'  => 'Sym',
            'image' => 'Image',
        ];
    }

    public function behaviors() {
        return [
            [
                'class'         => \maxmirazh33\image\Behavior::className(),
                'savePathAlias' => 'img/smiles/',
                'urlPrefix'     => '/img/smiles/',
                'crop'          => false,
                'attributes'    => [
                    'image' => [
                        'width'  => 25,
                        'height' => 25
                    ],
                ],
            ],
        ];
    }
}
