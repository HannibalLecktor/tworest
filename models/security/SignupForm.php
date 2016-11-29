<?php
namespace app\models\security;

use app\models\security\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    public function attributeLabels() {
        return [
            'username' => 'Имя пользователя',
            'email'    => 'Ваш email',
            'password' => 'Ваш пароль',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['username', 'required', 'message' => 'Обязательное поле'],
            ['username', 'filter', 'filter' => 'trim'],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required', 'message' => 'Обязательное поле'],
            ['email', 'email', 'message' => 'Эл. почта имеет неверный формат'],
            [
                'email',
                'unique',
                'targetClass' => User::className(),
                'message'     => 'Этот email адрес уже занят'
            ],
            ['password', 'required', 'message' => 'Обязательное поле'],
            [
                'password',
                'string',
                'length' => [6, 12],
                'tooShort' => "Минимальная длина пароля должна составлять 6 символов",
                'tooLong' => "Максимальная длина пароля должна составлять 12 символов"
            ],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup() {
        if($this->validate()) {
            $user = new User;
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if($user->save()) {
                return $user;
            }
        }

        return false;
    }
}