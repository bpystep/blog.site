<?php

require dirname(__DIR__) . '/vendor/yiisoft/yii2/BaseYii.php';

class Yii extends \yii\BaseYii
{
    /* @var admin\Application */
    public static $app;
}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = require dirname(__DIR__) . '/vendor/yiisoft/yii2/classes.php';
Yii::$classMap['admin\Application'] = __DIR__ . '/Application.php';
Yii::$container = new yii\di\Container();
