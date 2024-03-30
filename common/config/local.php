<?php

use common\components\Environment as Env;

$local['params'] = [
    'dev'    => Env::get('YII_ENV') == 'dev',
    'prod'   => Env::get('YII_ENV') == 'prod',
    'debug'  => Env::get('YII_DEBUG')
];

$local['aliases'] = [
    '@bower'     => '@vendor/bower-asset',
    '@npm'       => '@vendor/npm-asset',
    '@common'    => Env::get('PROJECT_ROOT') . '/common',
    '@commonUrl' => Env::get('DOMAIN_COMMON'),
    '@admin'     => Env::get('PROJECT_ROOT') . '/admin',
    '@adminUrl'  => Env::get('DOMAIN_ADMIN'),
    '@public'    => Env::get('PROJECT_ROOT') . '/public',
    '@publicUrl' => Env::get('DOMAIN_PUBLIC'),
    '@console'   => Env::get('PROJECT_ROOT') . '/console'
];

$local['db'] = [
    'class'             => \yii\db\Connection::class,
    'dsn'               => Env::get('DB_DSN'),
    'username'          => Env::get('DB_USERNAME'),
    'password'          => Env::get('DB_PASSWORD') ?: null,
    'charset'           => Env::get('DB_CHARSET'),
    'enableSchemaCache' => true
];

$local['formatter'] = [
    'dateFormat'      => 'php:Y-m-d',
    'datetimeFormat'  => 'php:Y-m-d H:i',
    'timeZone'        => Env::get('TZ'),
    'defaultTimeZone' => Env::get('TZ')
];

$local['session'] = [
    'cookieParams' => [
        'path'   => '/',
        'domain' => '.' . Env::get('DOMAIN')
    ]
];

$local['storage'] = [
    'class' => \common\components\storage\LocalStorage::class
];

$local['cache'] = [
    'class'     => \yii\redis\Cache::class,
    'redis'     => 'redis',
    'keyPrefix' => 'cache'
];

$local['redis'] = [
    'class'    => \yii\redis\Connection::class,
    'hostname' => Env::get('REDIS_HOSTNAME'),
    'port'     => Env::get('REDIS_PORT'),
    'password' => Env::get('REDIS_PASSWORD')
];

$local['request'] = [
    'cookieValidationKey' => Env::get('COOKIE_VALIDATION_KEY'),
    'baseUrl'             => Env::get('BASE_URL'),
    'csrfCookie'          => [
        'name'   => '_csrf',
        'path'   => '/',
        'domain' => '.' . Env::get('DOMAIN')
    ]
];

$local['errorHandler'] = [
    'errorAction' => 'default/error'
];

$local['log'] = [
    'traceLevel' => $local['params']['debug'] ? 3 : 0,
    'targets'    => [
        [
            'class'  => 'yii\log\FileTarget',
            'levels' => ['error', 'warning']
        ],
        [
            'class'          => 'yii\log\FileTarget',
            'levels'         => ['warning', 'error'],
            'categories'     => ['yii\queue\Queue'],
            'logFile'        => '@console/runtime/logs/queue/error.log',
            'exportInterval' => 1,
            'logVars'        => []
        ]
    ]
];

$local['queue'] = [
    'class'  => \yii\queue\redis\Queue::class,
    'as log' => \yii\queue\LogBehavior::class
];

$local['user'] = [
    'identityClass'  => \common\modules\user\models\User::class,
    'identityCookie' => [
        'name'   => '_identity',
        'path'   => '/',
        'domain' => '.' . Env::get('DOMAIN')
    ]
];

$local['urlManager'] = [
    'common' => [
        'class'               => 'common\components\UrlManager',
        'prefix'              => '@commonUrl',
        'enablePrettyUrl'     => true,
        'showScriptName'      => false,
        'enableStrictParsing' => false,
        'rules'               => require(__DIR__ . '/rules.php')
    ],
    'admin'  => [
        'class'               => 'common\components\UrlManager',
        'prefix'              => '@adminUrl',
        'enablePrettyUrl'     => true,
        'showScriptName'      => false,
        'enableStrictParsing' => false,
        'rules'               => require(dirname(__DIR__, 2) . '/admin/config/rules.php')
    ],
    'public' => [
        'class'               => 'common\components\UrlManager',
        'prefix'              => '@publicUrl',
        'enablePrettyUrl'     => true,
        'showScriptName'      => false,
        'enableStrictParsing' => false,
        'rules'               => require(dirname(__DIR__, 2) . '/public/config/rules.php')
    ]
];

$local['seo'] = [
    'class' => \common\components\Seo::class
];

return $local;
