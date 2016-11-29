<?php

namespace app\models;

use app\models\Language;
use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "pages".
 *
 * @property integer $id
 * @property string $name
 * @property string $text
 * @property string $code
 * @property integer $language_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $sort
 *
 * @property Language $language
 */
class Page extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code', 'language_id'], 'required'],
            [['text'], 'string'],
            [['language_id', 'created_at', 'updated_at', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'text' => Yii::t('app', 'Text'),
            'language_id' => Yii::t('app', 'Language ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'code' => Yii::t('app', 'Code'),
            'sort' => Yii::t('app', 'Sort'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }

    public function getLanguageName()
    {
        return $this->language->name;
    }

    public function getLanguageList()
    {
        $languages = Language::find()->asArray()->all();
        return ArrayHelper::map($languages, 'id', 'name');
    }
}
