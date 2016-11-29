<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "countries".
 *
 * @property integer $id
 * @property string $name
 * @property string $flag
 * @property string $capital_image
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property City[] $cities
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'countries';
    }

    public function fields() {
        return [
            'id',
            'name',
            'cities'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['capital_image'], 'string', 'max' => 255],
            [['flag'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'            => Yii::t('app', 'ID'),
            'name'          => Yii::t('app', 'Название'),
            'created_at'    => Yii::t('app', 'Created At'),
            'updated_at'    => Yii::t('app', 'Updated At'),
            'flag'          => Yii::t('app', 'Flag'),
            'capital_image' => Yii::t('app', 'Capital Image'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getCities() {
        return $this->hasMany(City::className(), ['country_id' => 'id'])->indexBy('id');
    }

    public function afterFind() {
        if(Yii::$app->language !== 'ru') {
            $this->name = Yii::t('app', $this->name);
        }
    }
}