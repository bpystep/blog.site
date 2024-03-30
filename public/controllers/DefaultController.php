<?php

namespace public\controllers;

use common\controllers\DefaultController as DefaultCommonController;

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
}
