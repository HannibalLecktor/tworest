<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "languages".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $flag
 * @property Page[] $pages
 */
class Language extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'languages';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['code'], 'required'],
            [['code'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 100],
            [['flag'], 'string', 'max' => 255],
            [['translit'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'flag' => Yii::t('app', 'Flag'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages() {
        return $this->hasMany(Page::className(), ['language_id' => 'id']);
    }

    public static function getList() {
        $languages = Language::find()->select(['id', 'code', 'name', 'flag'])->indexBy('code')->all();

        $languages = ArrayHelper::toArray($languages);

        return $languages;
    }

    public function afterFind() {
        if(Yii::$app->language !== 'ru') {
            $this->name = Yii::t('app', $this->name);
        }
    }
}
