<?php
namespace admin\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    /* @var string */
    public $basePath = '@webroot';

    /* @var string */
    public $baseUrl = '@web';

    /* @var string[] */
    public $css = [
        'css/styles.css'
    ];

    /* @var string[] */
    public $js = [
        'js/scripts.js'
    ];

    /* @var string[] */
    public $depends = [
        'admin\assets\MintonAsset'
    ];
}
