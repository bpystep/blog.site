<?php

namespace public\controllers;

class IndexController extends DefaultController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
