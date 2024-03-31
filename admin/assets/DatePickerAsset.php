<?php

namespace admin\assets;

use yii\web\AssetBundle;

class DatePickerAsset extends AssetBundle
{
    /* @var string */
    public $basePath = '@webroot/minton/';

    /* @var string */
    public $baseUrl = '@web/minton/';

    /* @var string[] */
    public $css = [
        'plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css'
    ];

    /* @var string[] */
    public $js = [
        'plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
        'plugins/bootstrap-datepicker/locales/bootstrap-datepicker.ru.min.js'
    ];

    /* @var string[] */
    public $depends = [
        'common\assets\CommonAsset',
        'yii\bootstrap4\BootstrapPluginAsset'
    ];
}
