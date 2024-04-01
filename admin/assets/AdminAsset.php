<?php
namespace admin\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
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
        'admin\assets\MintonAsset'
    ];
}
