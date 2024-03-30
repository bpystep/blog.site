<?php

require __DIR__ . '/../vendor/yiisoft/yii2/BaseYii.php';

class Yii extends \yii\BaseYii
{
    /* @var common\components\Application */
    public static $app;
}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = require __DIR__ . '/vendor/yiisoft/yii2/classes.php';
Yii::$classMap['common\Application'] = __DIR__ . '/Application.php';
Yii::$container = new yii\di\Container();
