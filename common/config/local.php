<?php

use common\components\Environment as Env;

$local['params'] = [
    'domain'            => Env::get('DOMAIN'),
    'dev'               => Env::get('YII_ENV') == 'dev',
    'prod'              => Env::get('YII_ENV') == 'prod',
    'debug'             => Env::get('YII_DEBUG')
];

$local['aliases'] = [
    '@bower'     => '@vendor/bower-asset',
    '@npm'       => '@vendor/npm-asset',
    '@public'    => dirname(dirname(__DIR__)) . '/public',
    '@publicUrl' => Env::get('HOST_SCHEME') . '://' . Env::get('DOMAIN_PUBLIC'),
    '@admin'     => dirname(dirname(__DIR__)) . '/admin',
    '@adminUrl'  => Env::get('HOST_SCHEME') . '://' . Env::get('DOMAIN_ADMIN'),
    '@common'    => dirname(dirname(__DIR__)) . '/common',
    '@commonUrl' => Env::get('HOST_SCHEME') . '://' . Env::get('DOMAIN_COMMON'),
    '@console'   => dirname(dirname(__DIR__)) . '/console'
];

$local['db'] = [
    'class'             => 'yii\db\Connection',
    'dsn'               => Env::get('DB_DSN'),
    'username'          => Env::get('DB_USERNAME'),
    'password'          => Env::get('DB_PASSWORD'),
    'charset'           => Env::get('DB_CHARSET'),
    'enableSchemaCache' => true
];

$local['formatter'] = [
    'class'           => 'common\components\Formatter',
    'dateFormat'      => 'php:Y-m-d',
    'datetimeFormat'  => 'php:Y-m-d H:i',
    'siteTimeZone'    => 'Europe/Moscow',
    'timeZone'        => Env::get('TZ'),
    'defaultTimeZone' => Env::get('TZ')
];

$local['session'] = [
    'cookieParams' => [
        'path'   => '/',
        'domain' => '.' . $local['params']['domain']
    ]
];

$local['storage'] = [
    'class' => 'common\components\storage\LocalStorage'
];

$local['cache'] = [
    'class'     => 'yii\redis\Cache',
    'redis'     => 'redis',
    'keyPrefix' => 'cache'
];

$local['redis'] = [
    'class'    => 'yii\redis\Connection',
    'hostname' => Env::get('REDIS_HOSTNAME'),
    'port'     => Env::get('REDIS_PORT'),
    'password' => Env::get('REDIS_PASSWORD')
];

$local['request'] = [
    'cookieValidationKey' => Env::get('COOKIE_VALIDATION_KEY'),
    'baseUrl'             => '',
    'csrfCookie'          => ['name' => '_csrf', 'path' => '/', 'domain' => '.' . $local['params']['domain']]
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
    'class'  => 'yii\queue\redis\Queue',
    'as log' => 'yii\queue\LogBehavior'
];

$local['user'] = [
    'identityClass'  => 'common\modules\user\models\User',
    'identityCookie' => [
        'name'   => '_identity',
        'path'   => '/',
        'domain' => '.' . $local['params']['domain']
    ]
];

$local['urlManager'] = [
    'common'   => [
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
        'rules'               => require(dirname(dirname(__DIR__)) . '/admin/config/rules.php')
    ],
    'public' => [
        'class'               => 'common\components\UrlManager',
        'prefix'              => '@publicUrl',
        'enablePrettyUrl'     => true,
        'showScriptName'      => false,
        'enableStrictParsing' => false,
        'rules'               => require(dirname(dirname(__DIR__)) . '/public/config/rules.php')
    ]
];

$local['seo'] = [
    'class' => 'common\components\Seo'
];

return $local;
