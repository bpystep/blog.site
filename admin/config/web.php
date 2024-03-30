<?php

use common\components\Environment as Env;

$local = require(dirname(__DIR__, 2) . '/common/config/local.php');
foreach (['params.php', 'params.local.php'] as $item) {
    if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $item)) {
        $local['params'] = yii\helpers\ArrayHelper::merge($local['params'], require __DIR__ . DIRECTORY_SEPARATOR . $item);
    }
}

$config = [
    'id'                  => 'admin',
    'name'                => Env::get('SITE_NAME') . ': ' . 'admin',
    'basePath'            => dirname(__DIR__),
    'vendorPath'          => dirname(__DIR__, 2) . '/vendor',
    'controllerNamespace' => 'admin\controllers',
    'defaultRoute'        => 'index/index',
    'language'            => 'ru-RU',
    'sourceLanguage'      => 'ru-RU',
    'bootstrap'           => [
        'log', 'queue'
    ],
    'aliases'             => $local['aliases'],
    'container'           => [
        'definitions' => [
            \common\assets\CommonAsset::class => ['basePath' => '@common/web', 'baseUrl' => '@commonUrl']
        ]
    ],
    'modules'             => [

    ],
    'components'          => [
        'db'               => $local['db'],
        'formatter'        => $local['formatter'],
        'storage'          => $local['storage'],
        'cache'            => $local['cache'],
        'redis'            => $local['redis'],
        'session'          => $local['session'],
        'request'          => $local['request'],
        'errorHandler'     => $local['errorHandler'],
        'log'              => $local['log'],
        'queue'            => $local['queue'],
        'user'             => $local['user'],
        'urlManager'       => $local['urlManager']['admin'],
        'urlManagerCommon' => $local['urlManager']['common'],
        'urlManagerPublic' => $local['urlManager']['public'],
        'i18n'             => [
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
        'assetManager'     => [
            'appendTimestamp' => true
        ]
    ],
    'params'              => $local['params']
];

return array_merge(array_merge(require(__DIR__ . '/../../common/config/env.php'), [
    'yiiPath' => dirname(__DIR__) . '/components/Yii.php'
]), ['web' => $config]);
