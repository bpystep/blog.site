<?php

namespace common\controllers;

use Yii;

class DefaultController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     * @throws yii\web\BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        return true;
    }
}
