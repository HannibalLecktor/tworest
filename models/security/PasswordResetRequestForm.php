<?php

namespace app\models\security;

use app\models\security\User;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [
                'email',
                'exist',
                'targetClass' => User::className(),
                'filter'      => ['status' => User::STATUS_ACTIVE],
                'message'     => 'Пользователь с таким адресом эл. почты не зарегистрирован'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail() {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email'  => $this->email,
        ]);
        if($user) {
            $newPassword = \Yii::$app->security->generateRandomString(12);
            $user->setPassword($newPassword);
            if($user->save()) {
                return \Yii::$app->mailer->compose([
                    'html' => 'reset-password-html',
                    'text' => 'reset-password-text'
                ], ['email' => $user->email, 'password' => $newPassword])
                    ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->name . ' robot'])
                    ->setTo($this->email)
                    ->setSubject('Восстановление пароля ' . \Yii::$app->name)
                    ->send();
            }
        }

        return false;
    }
}