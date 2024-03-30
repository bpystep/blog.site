<?php

namespace admin\components;

use common\components\UrlManager;

class Application extends \common\components\Application
{
    /**
     * @inheritdoc
     */
    public function getUrlManager(): UrlManager
    {
        return $this->get('urlManager');
    }

    /**
     * @inheritdoc
     */
    public function getUrlManagerAdmin(): UrlManager
    {
        return $this->getUrlManager();
    }

    /**
     * @inheritdoc
     */
    public function getUrlManagerCommon(): UrlManager
    {
        return $this->get('urlManagerAdmin');
    }
}
