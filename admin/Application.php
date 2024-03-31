<?php

namespace admin;

use common\components\UrlManager;
use yii\base\InvalidConfigException;
use yii\queue\redis\Queue;

/**
 * Class Application
 * @package admin
 *
 * @property-read Queue $queue
 */
class Application extends \common\Application
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
    public function getQueue(): Queue
    {
        return $this->get('queue');
    }
}
