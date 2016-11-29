<?php

namespace app\controllers;

use app\models\security\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\security\LoginForm;
use app\models\security\PasswordResetRequestForm;
use app\models\security\User;
use yii\helpers\Url;

class SecurityController extends \yii\web\Controller
{
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions() {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionLogin() {
        if(!\Yii::$app->user->isGuest) {
            $user = User::find()
                ->with('district')
                ->where(['id' => \Yii::$app->user->id])
                ->one();

            return $this->goHome();
        }
        $model = new LoginForm();
        if($model->load(Yii::$app->request->post()) && $model->login()) {
            if($district = $model->user->district_id) {
                $this->redirect('/chats/' . $district);
            } else {
                return $this->goBack();
            }

        } else {
            return Yii::$app->request->isAjax
                ? $this->renderAjax('login', compact('model'))
                : $this->render('login', compact('model'));
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup() {
        $signupForm = new SignupForm();
        if($signupForm->load(Yii::$app->request->post())) {
            if($user = $signupForm->signup()) {
                if(Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', compact('signupForm'));
    }

    public function actionResetPassword() {
        $passwordResetForm = new PasswordResetRequestForm();
        if($passwordResetForm->load(Yii::$app->request->post()) && $passwordResetForm->validate()) {
            if($passwordResetForm->sendEmail()) {
                $successMessage = 'Инструкции по восстановлению пароля выслана на почту';

                return $this->render('password_reset', compact('passwordResetForm', 'successMessage'));
            } else {
                $errorMessage = 'Ошибка при отправке письма';
                $this->render('password_reset', compact('passwordResetForm', 'errorMessage'));
            }
        }

        return $this->render('password_reset', compact('passwordResetForm'));
    }
}
