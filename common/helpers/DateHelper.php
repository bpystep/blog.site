<?php

namespace common\helpers;

use DateTime;
use DateTimeZone;
use Exception;
use IntlTimeZone;
use yii\helpers\ArrayHelper;
use Yii;

class DateHelper
{
    const SECOND        = 1;
    const MINUTE        = self::SECOND * 60;
    const HOUR          = self::MINUTE * 60;
    const DAY           = self::HOUR * 24;
    const WEEK          = self::DAY * 7;
    const MONTH_28_DAYS = self::DAY * 28;
    const MONTH_29_DAYS = self::DAY * 29;
    const MONTH_30_DAYS = self::DAY * 30;
    const MONTH_31_DAYS = self::DAY * 31;
    const YEAR_364      = self::DAY * 364;
    const YEAR_365      = self::DAY * 365;

    public static function getMonths(): array
    {
        return [
            1  => Yii::t('app', 'Январь'),
            2  => Yii::t('app', 'Февраль'),
            3  => Yii::t('app', 'Март'),
            4  => Yii::t('app', 'Апрель'),
            5  => Yii::t('app', 'Май'),
            6  => Yii::t('app', 'Июнь'),
            7  => Yii::t('app', 'Июль'),
            8  => Yii::t('app', 'Август'),
            9  => Yii::t('app', 'Сентябрь'),
            10 => Yii::t('app', 'Октябрь'),
            11 => Yii::t('app', 'Ноябрь'),
            12 => Yii::t('app', 'Декабрь')
        ];
    }

    public static function getDaysOfWeek(bool $short = false): array
    {
        return $short ? [
            1 => Yii::t('app', 'Пн'),
            2 => Yii::t('app', 'Вт'),
            3 => Yii::t('app', 'Ср'),
            4 => Yii::t('app', 'Чт'),
            5 => Yii::t('app', 'Пт'),
            6 => Yii::t('app', 'Сб'),
            7 => Yii::t('app', 'Вс')
        ] : [
            1 => Yii::t('app', 'Понедельник'),
            2 => Yii::t('app', 'Вторник'),
            3 => Yii::t('app', 'Среда'),
            4 => Yii::t('app', 'Четверг'),
            5 => Yii::t('app', 'Пятница'),
            6 => Yii::t('app', 'Суббота'),
            7 => Yii::t('app', 'Воскресенье')
        ];
    }

    public static function getTimezones(bool $assoc = false): array
    {
        date_default_timezone_set('UTC');

        foreach (DateTimeZone::listIdentifiers() as $i) {
            $dtz = new DateTimeZone($i);
            $tz = IntlTimeZone::createTimeZone($i);

            $name = $tz->getID() === 'Etc/Unknown' || $i === 'UTC' ? $i : $tz->getDisplayName(false, 3, 'RU-ru');
            $offset = $dtz->getOffset(new DateTime());
            $sign = ($offset < 0) ? '-' : '+';

            $tzs[] = [
                'code'   => $i,
                'name'   => '(UTC' . $sign . date('H:i', abs($offset)) . ') ' . $name,
                'offset' => $offset
            ];
        }

        ArrayHelper::multisort($tzs, ['offset', 'name']);
        return $assoc ? ArrayHelper::map($tzs, 'code', 'name') : $tzs;
    }

    public static function getDaysOfWeekTitle(int $dayNumber): string
    {
        return self::getDaysOfWeek()[$dayNumber];
    }

    public static function getHours(): array
    {
        return [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23];
    }

    public static function getAge(string $birthday): int
    {
        $from = new DateTime($birthday);
        $to = new DateTime('today');

        return $from->diff($to)->y;
    }

    public static function formatDate(string $date = null, bool $withTime = true, bool $shortMonth = false, bool $timestamp = false, bool $viewCurrentYear = false, bool $withDate = true): string
    {
        $formatter = Yii::$app->getFormatter();
        if ($date == 'empty' || empty($date)) {
            return $formatter->asDate(null);
        }

        if (!$withTime) {
            $withDate = true;
        }

        if ($withDate) {
            if ($timestamp) {
                $date = date('Y-m-d H:i', $date);
            }

            $monthPattern = $shortMonth ? 'MM' : 'MMMM';

            $pattern = ['dd', $monthPattern, 'yyyy'];
            $pattern = implode($shortMonth ? '.' : ' ', $pattern);

            if (!$viewCurrentYear) {
                $currentYear = date('Y');
                $dateYear = $formatter->asDate($date, 'yyyy');

                $pattern = ['dd', $monthPattern];
                if ($dateYear != $currentYear) {
                    $pattern[] = 'yyyy';
                }
                $pattern = implode($shortMonth ? '.' : ' ', $pattern);
            }

            if ($withTime) {
                $pattern .= ', H:mm';
            }
        } else {
            $withTime = true;
            $pattern = 'H:mm';
        }

        return $withTime ? $formatter->asTime($date, $pattern) : $formatter->asDate($date, $pattern);
    }

    public static function fromTimezone(string $date, string $timezone, string $format = null, bool $onlyTime = false): string|DateTime
    {
        if ($onlyTime) {
            $date = implode(' ', [date('Y-m-d'), $date]);
        }
        $dt = (new DateTime($date, new DateTimeZone($timezone)))
            ->setTimezone(new DateTimeZone(Yii::$app->formatter->defaultTimeZone));

        return $format ? $dt->format($format) : $dt;
    }

    public static function toTimezone(string $date, string $timezone, string $format = null, bool $onlyTime = false, bool $fromTimestamp = false): string|DateTime
    {
        if ($fromTimestamp) {
            $onlyTime = true;
        }

        if ($onlyTime) {
            $date = implode(' ', [!$fromTimestamp ? date('Y-m-d') : date('Y-m-d', $date), !$fromTimestamp ? $date : date('H:i:s', $date)]);
        }

        $dt = (new DateTime($date, new DateTimeZone(Yii::$app->formatter->defaultTimeZone)))
            ->setTimezone(new DateTimeZone($timezone));

        return $format ? $dt->format($format) : $dt;
    }

    /**
     * @throws Exception
     */
    public static function getTimezonesOffset($timezone): int
    {
        return (new DateTime('now', new DateTimeZone($timezone)))->getOffset() / 3600 - (new DateTime('now', new DateTimeZone(Yii::$app->formatter->defaultTimeZone)))->getOffset() / 3600;
    }
}
