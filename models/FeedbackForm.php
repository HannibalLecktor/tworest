<?php
/**
 * Created by PhpStorm.
 * User: Khadeev Fanis
 * Date: 11/10/15
 * Time: 19:30
 */

namespace app\models;

use yii\base\Model;

class FeedbackForm extends Model
{
    public $name;
    public $email;
    public $text;

    public function rules()
    {
        return [
            [['email', 'text'], 'required'],
            ['email', 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => \Yii::t('app', 'Email'),
            'name' => \Yii::t('app', 'Name'),
            'text' => \Yii::t('app', 'Text'),
        ];
    }
}