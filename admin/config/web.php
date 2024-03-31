<?php
use common\components\Environment as Env;

$local = require(dirname(dirname(__DIR__)) . '/common/config/local.php');
foreach (['params.php', 'params.local.php'] as $item) {
    if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $item)) {
        $local['params'] = yii\helpers\ArrayHelper::merge($local['params'], require __DIR__ . DIRECTORY_SEPARATOR . $item);
    }
}

$config = [
    'id'                  => 'admin',
    'name'                => Env::get('SITE_NAME') . ': ' . 'Административная панель',
    'basePath'            => dirname(__DIR__),
    'vendorPath'          => dirname(dirname(__DIR__)) . '/vendor',
    'controllerNamespace' => 'admin\controllers',
    'defaultRoute'        => 'main/index',
    'language'            => 'ru-RU',
    'sourceLanguage'      => 'ru-RU',
    'bootstrap'           => [
        'log', 'queue',
        'admin\modules\user\Bootstrap',
        'admin\modules\redaction\Bootstrap',
    ],
    'modules'             => [
        'user'      => 'admin\modules\user\Module',
        'redaction' => 'admin\modules\redaction\Module'
    ],
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
        'queue'            => $local['queue'],
        'user'             => $local['user'],
        'urlManager'       => $local['urlManager']['admin'],
        'urlManagerCommon' => $local['urlManager']['common'],
        'urlManagerPublic' => $local['urlManager']['public'],
        'i18n'             => [
            'translations' => [
                '*' => [
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@admin/messages'
                ]
            ]
        ],
        'assetManager'     => [
            //'appendTimestamp' => true,
            //'forceCopy' => true,
            'bundles' => [
                'dosamigos\datepicker\DatePickerAsset'         => ['js' => [], 'css' => [], 'depends' => ['admin\assets\DatePickerAsset']],
                'dosamigos\datetimepicker\DateTimePickerAsset' => ['js' => [], 'css' => [], 'depends' => ['admin\assets\DateTimePickerAsset']],
                'yii\web\JqueryAsset'                          => ['sourcePath' => '@admin/web/minton', 'js' => ['plugins/jquery/jquery.min.js']],
                'yii\bootstrap4\BootstrapAsset'                => ['css' => []],
                'yii\bootstrap4\BootstrapPluginAsset'          => ['sourcePath' => '@admin/web/minton', 'js' => ['plugins/bootstrap/js/bootstrap.bundle.min.js'], 'depends' => []]
            ]
        ]
    ],
    'params'              => $local['params']
];

return array_merge(array_merge(require(__DIR__ . '/../../common/config/env.php'), [
    'yiiPath' => dirname(__DIR__) . '/Yii.php'
]), ['web' => $config]);
