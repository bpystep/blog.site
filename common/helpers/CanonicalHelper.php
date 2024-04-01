<?php

namespace common\helpers;

use Yii;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\View;

class CanonicalHelper
{
    public static function register(View $view, string|array $url = null, int $page = null, Pagination $pagination = null): void
    {
        $urlManager = Yii::$app->urlManager;
        $scheme = Yii::$app->request->isSecureConnection ? 'https' : 'http';

        if ($url) {
            $canonicalUrl = is_string($url) ? $url : $urlManager->createAbsoluteUrl($url);
        } else {
            $canonicalUrl = Yii::$app->request->getAbsoluteUrl();
        }

        $view->registerLinkTag(['href' => Url::ensureScheme($canonicalUrl, $scheme), 'rel' => 'canonical']);

        if ($url && is_array($url)) {
            if (is_numeric($page) && $page > 0) {
                $prevUrl = $urlManager->createUrl(array_merge($url, ($page - 1) > 0 ? ['page' => $page - 1] : []));
                $view->registerLinkTag(['href' => Yii::$app->request->getHostInfo() . Url::ensureScheme($prevUrl, $scheme), 'rel' => 'prev']);
            }

            if ($pagination && (is_null($page) || $pagination->getPageCount() > $page)) {
                if (is_null($page)) {
                    $page = 1;
                }

                $nextUrl = $urlManager->createUrl(array_merge($url, ($page + 1) > 0 ? ['page' => $page + 1] : []));
                $view->registerLinkTag(['href' => Yii::$app->request->getHostInfo() . Url::ensureScheme($nextUrl, $scheme), 'rel' => 'next']);
            }
        }
    }
}
