<?php
use common\components\Environment as Env;

$local = require(dirname(dirname(__DIR__)) . '/common/config/local.php');
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
    'bootstrap'           => ['log', 'queue'],
    'controllerNamespace' => 'console\commands',
    'components'          => [
        'cache'   => $local['cache'],
        'user'    => [
            'class'         => \yii\web\User::class,
            'identityClass' => \common\modules\user\models\User::class
        ],
        'session' => ['class' => 'yii\web\Session'],
        'redis'   => $local['redis'],
        'db'      => $local['db'],
        'storage' => $local['storage'],
        'log'     => $local['log'],
        'queue'   => $local['queue'],
        'i18n'    => [
            'translations' => [
                '*' => [
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages'
                ]
            ]
        ],
    ],
    'params'              => $local['params']
];

return array_merge(array_merge(require(__DIR__ . '/../../common/config/env.php'), [
    'yiiPath' => __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php'
]), ['console' => $config]);
