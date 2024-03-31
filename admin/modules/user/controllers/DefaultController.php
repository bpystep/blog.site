<?php

namespace admin\modules\user\controllers;

use admin\controllers\DefaultController as AdminDefaultController;

class DefaultController extends AdminDefaultController
{
    /* @var string */
    public $layout = '/base';

    /**
     * @inheritdoc
     */
    public function beforeAction($action): bool
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        return true;
    }
}
