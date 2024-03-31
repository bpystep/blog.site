<?php
namespace admin\assets;

use yii\web\AssetBundle;

class ErrorAsset extends AssetBundle
{
    /* @var string */
    public $basePath = '@webroot';

    /* @var string */
    public $baseUrl = '@web';

    /* @var string[] */
    public $css = [
        'minton/css/default/bootstrap.min.css',
        'minton/css/default/app.min.css',
        'minton/css/icons.min.css',
    ];

    /* @var string[] */
    public $js = [
        'minton/js/vendor.min.js',
        'minton/js/app.min.js',
    ];

    /* @var string[] */
    public $depends = [
        'common\assets\CommonAsset',
        'yii\bootstrap4\BootstrapPluginAsset'
    ];
}
