<?php

use common\components\Environment as Env;

return [
    'yiiPath'     => dirname(__DIR__) . '/Yii.php',
    'yiiDebug'    => Env::get('YII_DEBUG'),
    'yiiEnv'      => Env::get('YII_ENV'),
    'maintenance' => Env::get('APP_MAINTENANCE')
];
