<?php

namespace public\assets;

use yii\web\AssetBundle;

/**
 * @inheritdoc
 */
class SmartyAsset extends AssetBundle
{
    public $basePath = '@webroot/smarty/';

    public $baseUrl = '@web/smarty/';

    public static bool $isDark = false;

    public static bool $isBoxed = false;

    public $css = [
        'css/essentials.css',
        'css/layout.css',
    ];

    public $js = [
        'plugins/toastr/toastr.js',
        'js/scripts.js',
    ];

    public $depends = [
        'common\assets\CommonAsset',
        'yii\bootstrap4\BootstrapPluginAsset'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (self::$isDark) {
            $this->css[] = 'css/layout-dark.css';
        }

        $this->css[] = 'css/headers/header-1.css';
        $this->css[] = 'css/colors/lightgrey.css';
        $this->css[] = 'css/styles.css';
        $this->css[] = 'css/redactor.css';
    }
}
