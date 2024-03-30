<?php

namespace common\helpers;

use Yii;
use yii\helpers\Html;

class ImageHelper
{
    const AVAILABLE_EXTENSIONS = 'jpg, jpeg, gif, png, webp, svg';

    public static function getImage(string $src, array $imageOptions = [], string|array $url = null, array $urlOptions = []): string
    {
        $image = Html::img(Yii::getAlias($src), $imageOptions);
        if ($url && is_array($url)) {
            $url = Yii::$app->urlManager->createUrl($url);
        }

        return $url ? Html::a($image, $url, $urlOptions) : $image;
    }
}
