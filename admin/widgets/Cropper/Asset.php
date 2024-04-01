<?php

namespace admin\widgets\Cropper;

use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    /* @inheritdoc */
    public $sourcePath = '@admin/widgets/Cropper/assets';

    /* @inheritdoc */
    public $css = [
        'css/cropper.min.css',
        'css/styles.css'
    ];

    /* @inheritdoc */
    public $js = [
        'js/cropper.min.js',
        'js/scripts.js'
    ];

    /* @inheritdoc */
    public $depends = [
        'admin\assets\AdminAsset'
    ];
}
