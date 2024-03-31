<?php
namespace common\assets;

use yii\web\AssetBundle;
use yii\web\View;

class CommonAsset extends AssetBundle
{
    /* @var string */
    public $basePath = '@common';

    /* @var string */
    public $baseUrl = '@commonUrl';

    /* @var string[] */
    public $css = [
        'css/styles.css'
    ];

    /* @var string[] */
    public $js = [
        'js/scripts.js'
    ];

    /* @var string[] */
    public $jsOptions = [
        'position' => View::POS_END
    ];

    /* @var string[] */
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
