<?php

namespace common\components;

use common\helpers\CanonicalHelper;
use Yii;
use yii\base\Component;
use yii\helpers\Html;
use yii\web\View;

class Seo extends Component
{
    private static array $params = [];

    public function registerMeta(View $view): void
    {
        if (empty(static::$params)) {
            $this->setMeta($view);
        }

        $metaTags = [
            ['name' => 'og:site_name',        'content' => Yii::$app->name],
            ['name' => 'og:type',             'content' => !empty(static::$params['type']) ? static::$params['type'] : null],
            ['name' => 'og:url',              'content' => $this->getUrl()],
            ['name' => 'og:title',            'content' => static::$params['title']],
            ['name' => 'twitter:title',       'content' => static::$params['title']],
            ['name' => 'description',         'content' => static::$params['description']],
            ['name' => 'og:description',      'content' => static::$params['description']],
            ['name' => 'twitter:description', 'content' => static::$params['description']],
            ['name' => 'keywords',            'content' => implode(',', array_filter(
                static::$params['keywords'], fn($value) => Html::encode(Yii::t('seo', $value))
            ))],
            ['name' => 'og:image',            'content' => static::$params['image'], 'property' => 'og:image']
        ];

        foreach ($metaTags as $metaTag) {
            if (!empty($metaTag['content'])) {
                $view->registerMetaTag(['name' => $metaTag['name'], 'content' => Html::encode($metaTag['content'])], $metaTag['name']);
                if (isset($metaTag['property'])) {
                    $view->registerMetaTag(['property' => $metaTag['property'], 'content' => Html::encode($metaTag['content'])]);
                }
            }
        }

        if (!isset(static::$params['canonical'])) {
            CanonicalHelper::register($view);
        }
    }

    public function setMeta(View $view, array $params = []): void
    {
        if (!empty($view->title)) {
            static::$params['title'] = $view->title;
        } else if (!empty($params['title'])) {
            static::$params['title'] = $params['title'];
        } else {
            static::$params['title'] = $this->getDefaultTitle();
        }

        if (!empty($params['description'])) {
            static::$params['description'] = $params['description'];
        } else {
            static::$params['description'] = $this->getDefaultDescription();
        }

        if (!empty($params['keywords'])) {
            static::$params['keywords'] = $params['keywords'];
        } else {
            static::$params['keywords'] = $this->getDefaultKeywords();
        }

        if (!empty($params['image'])) {
            static::$params['image'] = $params['image'];
        } else {
            static::$params['image'] = $this->getDefaultImage();
        }

        if (!empty($params['type'])) {
            static::$params['type'] = $params['type'];
        }

        if (!empty($params['canonical'])) {
            static::$params['canonical'] = $params['canonical'];
        }
    }

    private function getDefaultTitle(): string
    {
        return Yii::t('seo', 'Блог {0}', [Yii::$app->name]);
    }

    private function getDefaultDescription(): string
    {
        return Yii::t('seo', '{0} - Блог, новости', [Yii::$app->name]);
    }

    private function getDefaultKeywords(): array
    {
        return [Yii::$app->name];
    }

    private function getDefaultImage(): string
    {
        return Yii::getAlias('@commonUrl/imageslogo.png/logo.png');
    }

    public function getH1(View $view): string
    {
        return $view->title ?: $this->getDefaultTitle();
    }

    public function getUrl(): string
    {
        return Yii::$app->request->getAbsoluteUrl();
    }
}
