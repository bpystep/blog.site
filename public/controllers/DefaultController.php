<?php

namespace public\controllers;

use common\controllers\DefaultController as DefaultCommonController;
use common\models\Settings;
use Yii;

class DefaultController extends DefaultCommonController
{
    public $layout = '/base';

    public bool $transparentHeader = false;

    public Settings $settings;

    public function beforeAction($action): bool
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $this->settings = Settings::findOne(Settings::SETTINGS_ID);

        return true;
    }

    /**
     * @return string|boolean
     */
    public function actionError(): string|bool
    {
        $this->layout = '/default';
        $this->transparentHeader = true;

        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }

        return false;
    }
}
