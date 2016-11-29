<?php
namespace app\models\security;

use Yii;
use yii\base\Model;

class ChangePasswordForm extends Model
{
    public $newpass;
    public $repeatnewpass;

    public function rules() {
        return [
            [['newpass', 'repeatnewpass'], 'required'],
            ['repeatnewpass', 'compare', 'compareAttribute' => 'newpass'],
            [
                'newpass',
                'string',
                'length' => [6, 12],
                'tooShort' => "Минимальная длина пароля должна составлять 6 символов",
                'tooLong' => "Максимальная длина пароля должна составлять 12 символов"
            ],
        ];
    }

    public function attributeLabels() {
        return [
            'newpass'       => Yii::t('app', 'New Password'),
            'repeatnewpass' => Yii::t('app', 'Repeat New Password'),
        ];
    }
}