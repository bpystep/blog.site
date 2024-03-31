<?php

namespace common\constants;

class PublishStatuses extends AbstractConstants
{
    const STATUS_DRAFT   = -1;
    const STATUS_WAITING = 0;
    const STATUS_PUBLIC  = 1;

    public static function getData(): array
    {
        return [
            self::STATUS_DRAFT   => 'Черновик',
            self::STATUS_WAITING => 'Отложенная',
            self::STATUS_PUBLIC  => 'Опубликовано'
        ];
    }
}
