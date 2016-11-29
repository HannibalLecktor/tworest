<?php

$config = [
    'id'         => 'basic',
    'basePath'   => dirname(__DIR__),
    'bootstrap'  => [
        'log',
        [
            'class' => 'app\components\LanguageSelector'
        ],
    ],
    'components' => [
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
                ''                               => 'pages/home',
                'signup'                         => 'security/signup',
                'login'                          => 'security/login',
                'logout'                         => 'security/logout',
                'reset-password'                 => 'security/reset-password',
                'chats/<id:\d+>'                 => 'chats/show',
                'admin'                          => 'pages/admin',
                'privatechats/<id:\d+>'          => 'private-chats/show',
                'feedback'                       => 'pages/feedback',
                '<action:country|city|district>' => 'pages/home',
                '<controller:chats|country|district|city>/<id:\d+>' => '<controller>/view',
                '<action:\w+>'                   => 'static/index',
            ],
            'class'           => 'yii\web\UrlManager',
        ],
        'session'      => [
            'name'         => '_tworest_session',
            'class'        => 'app\components\CustomSession',
            'cookieParams' => [
                'httpOnly' => false,
            ],
        ],
        'request'      => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'NNWjga6bxAedQ98jqBiVk02hieVOY_DELWIk',
        ],
        'user'         => [
            'identityClass'   => 'app\models\security\User',
            'enableAutoLogin' => true,
            'loginUrl'        => ['security/login'],
        ],
        //        'errorHandler' => [
        //            'errorAction' => 'pages/error',
        //        ],
        'mailer'       => [
            'class'            => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class'    => 'Swift_SmtpTransport',
                'host'     => 'smtp.gmail.com',
                'username' => 'fans7288@gmail.com',
                'password' => 'lt ,hjqkm',
                'port'     => '587',
                'encryption' => 'tls',
            ]
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db'           => require(__DIR__ . '/db.php'),
        'assetManager' => [
            'class'     => 'yii\web\AssetManager',
            'forceCopy' => false,
        ],
        'i18n'         => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en',
                ],
            ],
        ],
        'cache'        => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
    'params'     => require(__DIR__ . '/params.php'),
    'aliases'    => [
        '@upload' => '/img/upload',
    ],
    'language'   => 'ru',
];

if(YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
