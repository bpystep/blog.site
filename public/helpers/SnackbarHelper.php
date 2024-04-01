<?php

namespace public\helpers;

use Yii;
use yii\web\View;

class SnackbarHelper
{
    public static function register(View $view): void
    {
        foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
            $view->registerJs("
                $(document).ready(function() {
                    _toastr('" . $message . "', 'top-right', '" . $type . "', false);
                });
            ");
        }
    }
}
