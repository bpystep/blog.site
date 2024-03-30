<?php

namespace common\components;

use Yii;
use yii\web\UrlManager as BaseUrlManager;

class UrlManager extends BaseUrlManager
{
    public string $prefix = '';

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        if ($alias = Yii::getAlias($this->prefix)) {
            $this->prefix = $alias;
        }

        parent::init();
    }

    /**
     * @param $params array|string
     */
    public function createUrl($params): string
    {
        return $this->prefix . parent::createUrl($params);
    }
}
