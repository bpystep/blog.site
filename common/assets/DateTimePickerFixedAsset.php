<?php
namespace common\assets;

use yii\web\AssetBundle;

class DateTimePickerFixedAsset extends AssetBundle
{
    /* @var string */
    public $basePath = '@common';

    /* @var string */
    public $baseUrl = '@commonUrl';

    /* @var string[] */
    public $js = [
        'plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js'
    ];

    /* @var string[] */
    public $depends = [
        'yii\bootstrap4\BootstrapPluginAsset'
    ];
}
