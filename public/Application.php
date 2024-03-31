<?php

namespace public;

use common\components\Seo;
use common\components\UrlManager;
use yii\base\InvalidConfigException;

/**
 * Class Application
 * @package public
 *
 * @property-read Seo $seo
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
    public function getSeo(): Seo
    {
        return $this->get('seo');
    }
}
