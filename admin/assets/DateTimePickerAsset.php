<?php
namespace admin\assets;

use yii\web\AssetBundle;

class DateTimePickerAsset extends AssetBundle
{
    /* @var string */
    public $basePath = '@webroot/minton/';

    /* @var string */
    public $baseUrl = '@web/minton/';

    /* @var string[] */
    public $css = [
        'plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker-smalot.min.css',
        'plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css'
    ];

    /* @var string[] */
    public $js = [
        'plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js'
    ];

    /* @var string[] */
    public $depends = [
        'common\assets\CommonAsset',
        'yii\bootstrap4\BootstrapPluginAsset'
    ];
}
