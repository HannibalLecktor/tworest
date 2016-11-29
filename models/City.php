<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "cities".
 *
 * @property integer $id
 * @property string $name
 * @property string $image
 * @property integer $country_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Country $country
 * @property District[] $districts
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'cities';
    }

    public function fields() {
        return [
            'id',
            'name',
            'districts'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'country_id'], 'required'],
            [['country_id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'         => Yii::t('app', 'ID'),
            'name'       => Yii::t('app', 'Name'),
            'image'       => Yii::t('app', 'Image'),
            'country_id' => Yii::t('app', 'Country ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
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
    public function getCountry() {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistricts() {
        return $this->hasMany(District::className(), ['city_id' => 'id'])->indexBy('id');
    }

    public function afterFind() {
        if(Yii::$app->language !== 'ru') {
            $this->name = Yii::t('app', $this->name);
        }
    }
}
