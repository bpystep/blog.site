<?php

namespace public\controllers;

class MainController extends DefaultController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
