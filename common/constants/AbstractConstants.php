<?php

namespace common\constants;

use Yii;
use yii\helpers\ArrayHelper;

abstract class AbstractConstants
{
    protected static $defaultMessageCategory = 'constants';

    abstract public static function getData(): array;

    /**
     * @param $const string
     * @return string
     * @throws \Exception
     */
    public static function getTitle($const): string
    {
        return Yii::t(static::$defaultMessageCategory, ArrayHelper::getValue(static::getAll(), $const, 'Неизвестно'));
    }

    public static function getAll(): array
    {
        return array_map(function($item) {
            return is_string($item) ? Yii::t(static::$defaultMessageCategory, $item) : $item;
        }, static::getData());
    }
}
