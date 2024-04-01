<?php

namespace public\controllers;

use common\modules\redaction\models\Category;
use common\modules\redaction\models\Post;
use common\modules\redaction\models\Tag;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class MainController extends DefaultController
{
    const LIMIT_INDEX_SLIDES = 5;
    const LIMIT_INDEX_POSTS  = 3;

    const LIMIT_PROFILE_SLIDES = 4;
    const LIMIT_PROFILE_POSTS  = 4;

    public function actionIndex(): string
    {
        $slides = Post::find()->inSlider()->published()->sort()->limit(self::LIMIT_INDEX_SLIDES)->all();
        $posts = Post::find()->onMain()->published()->sort()->limit(self::LIMIT_INDEX_POSTS)->exclude(ArrayHelper::getColumn($slides, 'poser_id'))->all();

        return $this->render('index', [
            'slides' => $slides,
            'posts'  => $posts
        ]);
    }

    public function actionProfile(): string
    {
        $slides = Post::find()->inSlider()->published()->sort()->limit(self::LIMIT_PROFILE_SLIDES)->all();
        $posts = Post::find()->onMain()->published()->sort()->limit(self::LIMIT_PROFILE_POSTS)->exclude(ArrayHelper::getColumn($slides, 'poser_id'))->all();

        return $this->render('profile', [
            'slides' => $slides,
            'posts'  => $posts
        ]);
    }

    public function actionNews(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Post::find()->published()->sort()
        ]);

        $categories = Category::find()->sort()->all();
        $tags = Tag::find()->sort()->all();

        $recent = Post::find()->published()->sort()->limit(3)->all();
        $popular = Post::find()->exclude(ArrayHelper::getColumn($recent, 'post_id'))->published()->sort()->limit(3)->all();

        return $this->render('news', [
            'dataProvider' => $dataProvider,
            'categories'   => $categories,
            'tags'         => $tags,
            'recent'       => $recent,
            'popular'      => $popular
        ]);
    }
}
