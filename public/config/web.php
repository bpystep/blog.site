<?php
use common\components\Environment as Env;

$local = require(dirname(dirname(__DIR__)) . '/common/config/local.php');
foreach (['params.php', 'params.local.php'] as $item) {
    if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $item)) {
        $local['params'] = yii\helpers\ArrayHelper::merge($local['params'], require __DIR__ . DIRECTORY_SEPARATOR . $item);
    }
}

$config = [
    'id'                  => 'public',
    'name'                => Env::get('SITE_NAME'),
    'basePath'            => dirname(__DIR__),
    'vendorPath'          => dirname(dirname(__DIR__)) . '/vendor',
    'controllerNamespace' => 'public\controllers',
    'defaultRoute'        => 'main/index',
    'language'            => 'ru-RU',
    'sourceLanguage'      => 'ru-RU',
    'bootstrap'           => ['log'],
    'aliases'             => $local['aliases'],
    'container'           => [
        'definitions' => [
            'common\assets\CommonAsset' => ['basePath' => '@common/web', 'baseUrl' => '@commonUrl']
        ]
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
        'user'             => $local['user'],
        'urlManager'       => $local['urlManager']['public'],
        'urlManagerCommon' => $local['urlManager']['common'],
        'urlManagerAdmin'  => $local['urlManager']['admin'],
        'seo'              => $local['seo'],
        'i18n'             => [
            'translations' => [
                '*' => [
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@public/messages'
                ]
            ]
        ],
        'assetManager'     => [
            'appendTimestamp' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => '@public/web/smarty',
                    'js'         => ['plugins/jquery/jquery-3.2.1.min.js']
                ],
                'yii\bootstrap4\BootstrapPluginAsset' => [
                    'sourcePath' => '@public/web/smarty',
                    'css'        => ['plugins/bootstrap/css/bootstrap.min.css'],
                    'js'         => ['plugins/bootstrap/js/bootstrap.bundle.js'],
                    'depends'    => []
                ]
            ]
        ]
    ],
    'params'              => $local['params']
];

return array_merge(array_merge(require(__DIR__ . '/../../common/config/env.php'), [
    'yiiPath' => dirname(__DIR__) . '/Yii.php'
]), ['web' => $config]);
