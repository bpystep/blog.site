<?php

namespace common\components;

use common\components\abstracts\User;
use yii\base\InvalidConfigException;

/**
 * Defined properties:
 * @property-read User       $user
 * @property-read UrlManager $urlManager
 * @property-read UrlManager $urlManagerCommon
 * @property-read UrlManager $urlManagerAdmin
 * @property-read UrlManager $urlManagerPublic
 * @property-read Seo        $seo
 */
class Application extends \yii\web\Application
{
    /**
     * @throws InvalidConfigException
     */
    public function getUrlManager(): UrlManager
    {
        return $this->getUrlManagerCommon();
    }

    /**
     * @throws InvalidConfigException
     */
    public function getUrlManagerCommon(): UrlManager
    {
        return $this->get('urlManager');
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
