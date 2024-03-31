<?php
namespace admin\controllers;

use common\controllers\DefaultController as DefaultCommonController;
use Yii;

class DefaultController extends DefaultCommonController
{
    public $layout = '/base';

    public function beforeAction($action): bool
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        return true;
    }

    public function actionError(): string|false
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            $this->layout = 'error';
            if ($exception->statusCode == 404) {
                return $this->render('404', ['exception' => $exception]);
            }

            return $this->render('error', ['exception' => $exception]);
        }

        return false;
    }
}
