<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;

/**
 * This is the model class for table "districts".
 *
 * @property integer $id
 * @property string $name
 * @property integer $city_id
 * @property string $image
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property City $city
 */
class District extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'districts';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'city_id'], 'required'],
            [['city_id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 64],
            //[['image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'         => Yii::t('app', 'ID'),
            'name'       => Yii::t('app', 'Название'),
            'city_id'    => Yii::t('app', 'Город'),
            'image' => Yii::t('app', 'Image'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity() {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    public function afterFind() {
        if(Yii::$app->language !== 'ru') {
            $lang = Yii::$app->cache->get('language');
            if(!$lang['translit']) {
                $lang['translit'] = Language::findOne(['code' => 'en'])->translit;
            }
            try {
                $this->name = strtr($this->name, Json::decode($lang['translit']));
            } catch(\Exception $e) {

            }
        }
    }
}
