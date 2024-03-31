<?php

namespace common;

use common\components\UrlManager;
use yii\base\InvalidConfigException;

/**
 * Class Application
 * @package common
 *
 * @property-read UrlManager $urlManager
 * @property-read UrlManager $urlManagerCommon
 * @property-read UrlManager $urlManagerAdmin
 * @property-read UrlManager $urlManagerPublic
 */
class Application extends \yii\web\Application
{
    /**
     * @throws InvalidConfigException
     */
    public function getUrlManager(): UrlManager
    {
        return $this->get('urlManager');
    }

    /**
     * @throws InvalidConfigException
     */
    public function getUrlManagerCommon(): UrlManager
    {
        return $this->get('urlManagerCommon');
    }

    /**
     * @throws InvalidConfigException
     */
    public function getUrlManagerAdmin(): UrlManager
    {
        return $this->get('urlManagerAdmin');
    }

    /**
     * @throws InvalidConfigException
     */
    public function getUrlManagerPublic(): UrlManager
    {
        return $this->get('urlManagerPublic');
    }
}
