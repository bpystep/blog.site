<?php

use common\components\Environment as Env;

$local = require(dirname(__DIR__, 2) . '/common/config/local.php');
foreach (['params.php', 'params.local.php'] as $item) {
    if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $item)) {
        $local['params'] = yii\helpers\ArrayHelper::merge($local['params'], require __DIR__ . DIRECTORY_SEPARATOR . $item);
    }
}
$config = [
    'id'                  => 'console',
    'name'                => Env::get('SITE_NAME') . ': ' . 'console',
    'basePath'            => dirname(__DIR__),
    'language'            => 'ru-RU',
    'sourceLanguage'      => 'ru-RU',
    'aliases'             => $local['aliases'],
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'console\commands',
    'components'          => [
        'cache'   => $local['cache'],
        'user'    => [
            'class'         => \common\components\abstracts\User::class,
            'identityClass' => \common\modules\user\models\User::class
        ],
        'session' => ['class' => \yii\web\Session::class],
        'redis'   => $local['redis'],
        'db'      => $local['db'],
        'log'     => $local['log'],
        'i18n'    => [
            'translations' => [
                '*' => [
                    'class'    => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@common/messages',
                    'fileMap'  => [
                        'app' => 'app.php'
                    ]
                ]
            ]
        ],
    ],
    'params'              => $local['params']
];

return array_merge(array_merge(require(__DIR__ . '/../../common/config/env.php'), [
    'yiiPath' => __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php'
]), ['console' => $config]);
