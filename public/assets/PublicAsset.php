<?php

namespace public\assets;

use yii\web\AssetBundle;

class PublicAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'css/styles.css'
    ];

    public $js = [
        'js/scripts.js'
    ];

    public $depends = [
        'public\assets\SmartyAsset'
    ];
}
