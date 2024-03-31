<?php
use common\components\Environment as Env;

$local = require(__DIR__ . '/local.php');
foreach (['params.php', 'params.local.php'] as $item) {
    if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $item)) {
        $local['params'] = yii\helpers\ArrayHelper::merge($local['params'], require __DIR__ . DIRECTORY_SEPARATOR . $item);
    }
}

$config = [
    'id'                  => 'common',
    'name'                => Env::get('SITE_NAME') . ': ' . 'common',
    'basePath'            => dirname(__DIR__),
    'vendorPath'          => dirname(dirname(__DIR__)) . '/vendor',
    'controllerNamespace' => 'common\controllers',
    'defaultRoute'        => 'main/index',
    'language'            => 'ru-RU',
    'sourceLanguage'      => 'ru-RU',
    'aliases'             => $local['aliases'],
    'components'          => [
        'db'               => $local['db'],
        'formatter'        => $local['formatter'],
        'cache'            => $local['cache'],
        'redis'            => $local['redis'],
        'session'          => $local['session'],
        'request'          => $local['request'],
        'errorHandler'     => $local['errorHandler'],
        'log'              => $local['log'],
        'user'             => $local['user'],
        'urlManager'       => $local['urlManager']['common'],
        'urlManagerAdmin'  => $local['urlManager']['admin'],
        'urlManagerPublic' => $local['urlManager']['public'],
        'i18n'             => [
            'translations' => [
                '*' => [
                    'class'    => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@common/messages'
                ]
            ]
        ]
    ],
    'params'              => $local['params']
];

return array_merge(require(__DIR__ . 'env.php'), ['web' => $config]);
