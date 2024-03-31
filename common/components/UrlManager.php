<?php
namespace common\components;

use Yii;
use yii\web\UrlManager as BaseUrlManager;

class UrlManager extends BaseUrlManager
{
    /* @var $prefix string */
    public $prefix = '';

    public function init()
    {
        if ($alias = Yii::getAlias($this->prefix)) {
            $this->prefix = $alias;
        }

        parent::init();
    }

    public function createUrl($params)
    {
        return $this->prefix . parent::createUrl($params);
    }
}