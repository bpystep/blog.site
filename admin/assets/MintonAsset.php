<?php
namespace admin\assets;

use yii\web\AssetBundle;

class MintonAsset extends AssetBundle
{
    /* @var string */
    public $basePath = '@webroot/minton/';

    /* @var string */
    public $baseUrl = '@web/minton/';

    /* @var string[] */
    public $css = [
        'plugins/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css',
        'css/default/bootstrap.min.css',
        'plugins/select2/css/select2.min.css',
        'plugins/admin-resources/rwd-table/rwd-table.min.css',
        'plugins//sweetalert2/sweetalert2.min.css',
        'css/default/app.min.css',
        'css/icons.min.css',
    ];

    /* @var string[] */
    public $js = [
        'js/vendor.min.js',
        'plugins/select2/js/select2.min.js',
        'plugins/select2/js/i18n/ru.js',
        'plugins/sweetalert2/sweetalert2.min.js',
        'plugins/tippy.js/tippy.all.min.js',
        'js/app.min.js',
    ];

    /* @var string[] */
    public $depends = [
        'dosamigos\datepicker\DatePickerAsset',
        'common\assets\CommonAsset',
        'yii\bootstrap4\BootstrapPluginAsset'
    ];
}
