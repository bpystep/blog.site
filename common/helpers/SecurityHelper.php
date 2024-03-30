<?php

namespace common\helpers;

use Yii;

class SecurityHelper
{
    /**
     * @param $length integer
     * @return integer
     */
    public static function generateRandomCode(int $length = 4): int
    {
        if ($length < 1) {
            $length = 1;
        }

        //dev среда всегда возвращает код, состоящий из 4 нулей
        if (Yii::$app->params['dev']) {
            return str_repeat('0', $length);
        }

        $min = '1'; $max = '9';
        for ($i = 1; $i < $length; $i++) {
            $min .= '0'; $max .= '9';
        }

        return rand((int)$min, (int)$max);
    }
}
