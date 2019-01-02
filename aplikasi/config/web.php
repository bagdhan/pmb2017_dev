<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'name' => 'PMB Pascasarjana',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','languageSwitcher', 'admin'],
    'timeZone' => 'Asia/Jakarta',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Mo2FqKqo1NyLbmTMOHUvcixyxMIhMw6K',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\usermanager\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-pascaIPB', ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails. p4sc4ipb
//            'transport' => [
//                'class' => 'Swift_SmtpTransport',
//                'host' => 'smtp.gmail.com',
//                'username' => 'spsipb@gmail.com',
//                'password' => 'p4sc4ipb',
//                'port' => '587',
//                'encryption' => 'tls',
//            ],

            'viewPath' => '@app/mail',
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
        'db' => require(__DIR__ . '/db.php'),
        /**/
        'urlManager' => [
            'class' => 'app\components\ZUrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<language:\w+>/'=>'site/index',
                '<language:\w+>/<action:(login|logout|about|contact|register|profile)>' => 'site/<action>',
                '<language:\w+>/<controller>/v/<id:\d+>' => '<controller>/view',
                '<language:\w+>/<controller>/u/<id:\d+>' => '<controller>/update',
                '<language:\w+>/<controller>/' => '<controller>/index',
                '<language:\w+>/<controller>/<action>' => '<controller>/<action>',
                '<language:\w+>/<controller:[\w\-]+>/<action:[\w\-]+>/<id:\d+>' => '<controller>/<action>',
                '<language:\w+>/<controller:[\w\-]+>/<action:[\w\-]+>' => '<controller>/<action>',
                //'<module>/index' => '<module>/default/index',
                '<language:\w+>/<module>/<controller>/v/<id:\d+>' => '<module>/<controller>/view',
                '<language:\w+>/<module>/<controller>/u/<id:\d+>' => '<module>/<controller>/update',
                '<language:\w+>/<module>/<controller>/' => '<module>/<controller>/index',
                '<language:\w+>/<module>/<controller:[\w\-]+>/<action:[\w\-]+>/<id:\d+>' => '<module>/<controller>/<action>',
                '<language:\w+>/<module>/<controller:[\w\-]+>/<action:[\w\-]+>' => '<module>/<controller>/<action>',
            ],
        ],

        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@app/adminlte/views/yiisoft/yii2-app'
                ],
            ],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => '_identity-pmbpasca',
        ],

        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app/messages',
                    'basePath' => '@app/adminlte/assets/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
                'pmb' => [
                    'class' => 'app\adminlte\assets\TranslatePMB',
                    'basePath' => '@app/adminlte/assets/messages',
                    'sourceLanguage' => 'en-US',
                ],
            ],
        ],
        'assetManager' => [
            'linkAssets' => false,
        ],
        'formatter' => [
            'dateFormat' => 'dd - MM - yyyy',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'ID',
        ],
        'languageSwitcher' => [
            'class' => 'app\components\languageSwitcher',
        ],
    ],
    'aliases' => [
        '@dirweb' => __DIR__.'/../web',
        '@arsipdir' => __DIR__.'/../uploaded/arsip',
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'usermanager' => [
            'class' => 'app\usermanager\usermanager',
        ],
        'cruddb' => [
            'class' => 'app\modules\cruddb\Module',
        ],
        'pendaftaran' => [
            'class' => 'app\modules\pendaftaran\Module',
        ],
        'pleno' => [
            'class' => 'app\modules\pleno\Module',
        ],
        'verifikasi' => [
            'class' => 'app\modules\verifikasi\Module',
        ],
    ],

    'language' => 'id',

    'sourceLanguage' => 'en-US',

    'params' => $params,
];

if (YII_ENV_DEV) {
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
