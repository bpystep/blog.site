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

    /**
     * @return string
     */
    public function actionPosts()
    {
        return $this->render('posts');
    }
}
