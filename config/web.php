<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        ],
   // 'language' => 'ru-RU',
    'language' => 'en-US',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'wcontroller' => 'app\components\widgets\controllers\WidgetController',
    ],

    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'sdfgasdfgsdfgfdg63456#$@#$%xcbdfgb',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
           // 'identityClass' => 'app\models\User',

            'class' => 'app\components\UserX',
            'identityClass' => 'app\modules\adminx\models\User',
            'loginUrl' => ['adminx/user/login'],
            'enableAutoLogin' => false,
        ],
        'authManager' => [
            'class' => 'app\modules\adminx\components\DbManager', // or use 'yii\rbac\DbManager'
          //  'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
            'cache' => 'cache' //Включаем кеширование
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'smtpXleMailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
                'username' => 'oleksii.xle.fish@gmail.com',
             //   'login' => 'whitesnake1969',
                'password' => 'fish140269',
                'port' => '587', // Port 25 is a very common port too '567'
                'encryption' => 'tls', // It is often used, check your provider or mail server specs
            ],
            'useFileTransport' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'conservation' => [
            'class' => 'app\components\conservation\ConservationComponent',
        ],
        'configs' => [
            'class' => 'app\components\configs\ConfigsComponent',
        ],
        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey' => '6LfU-p8UAAAAAOSjC2aMujiIuD9K8zw7tP4IJQrp',
            'secret' => '6LfU-p8UAAAAAJIytAMOw7CMnd8K5HmVaP0vT49-',
        ],
        'i18n' => [
            'translations' => [
                'app' => [
                  //  'class' => 'yii\i18n\DbMessageSource',
                    'class' => 'app\components\DbMessageSource',
                 //   'class' => 'app\i18n\PhpMessageSource',
                    //'basePath' => '@app/messages',
                    'sourceLanguage' => 'ru-RU',
                    /*
                    'fileMap' => [
                        'app'       => 'app.php',
                        'app/error' => 'error.php',
                    ],
                    */
                ],
            ],
        ],
    ],
    'modules' => [
        'adminx' => [
            'class' => 'app\modules\adminx\Adminx',
        ],
    ],

    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
