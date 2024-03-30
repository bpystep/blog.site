<?php

namespace public\controllers;

class PostsController extends DefaultController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
