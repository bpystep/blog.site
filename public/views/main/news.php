<?php
use common\helpers\DateHelper;
use yii\helpers\Html;

/* @var $this         yii\web\View */
/* @var $controller   public\controllers\MainController */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $categories   common\modules\redaction\models\Category[] */
/* @var $tags         common\modules\redaction\models\Tag[] */
/* @var $recent       common\modules\redaction\models\Post[] */
/* @var $popular      common\modules\redaction\models\Post[] */

$this->title = Yii::$app->name;
Yii::$app->seo->setMeta($this);

$controller = $this->context;
?>

<section>
    <div class="container">
        <div class="text-center">
            <h1 class="mb-0"><?php echo Yii::t('*', 'А это наши новости'); ?></h1>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="side-nav mb-60 mt-30">
                    <div class="side-nav-head">
                        <button class="fa fa-bars"></button>
                        <h4><?php echo Yii::t('*', 'Категории'); ?></h4>
                    </div>
                    <ul class="list-group list-group-bordered list-group-noicon uppercase">
                        <?php foreach ($categories as $category) { ?>
                            <li class="list-group-item"><a href="#"><span class="fs-11 text-muted float-right"><?php echo $category->getPosts()->count(); ?></span> <?php echo $category->title; ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="tabs mt-0 hidden-xs-down mb-60">
                    <ul class="nav nav-tabs nav-bottom-border nav-justified">
                        <li class="nav-item">
                            <a class="nav-item active" href="#tab_1" data-toggle="tab">
                                <?php echo Yii::t('*', 'Популярные'); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-item" href="#tab_2" data-toggle="tab">
                                <?php echo Yii::t('*', 'Свежие'); ?>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mb-60 mt-30">
                        <div id="tab_1" class="tab-pane active">
                            <?php foreach ($popular as $post) { ?>
                                <div class="row tab-post">
                                    <div class="col-md-3 col-sm-3 col-3">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(['/redaction/posts/view', 'id' => $post->post_id]); ?>">
                                            <img src="<?php echo $post->getImageUrl('image', '870x490'); ?>" width="50" alt="" />
                                        </a>
                                    </div>
                                    <div class="col-md-9 col-sm-9 col-9">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(['/redaction/posts/view', 'id' => $post->post_id]); ?>" class="tab-post-link"><?php echo $post->title; ?></a>
                                        <small><?php echo DateHelper::formatDate($post->published_dt, false); ?></small>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div id="tab_2" class="tab-pane">
                            <?php foreach ($recent as $post) { ?>
                                <div class="row tab-post">
                                    <div class="col-md-3 col-sm-3 col-3">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(['/redaction/posts/view', 'id' => $post->post_id]); ?>">
                                            <img src="<?php echo $post->getImageUrl('image', '870x490'); ?>" width="50" alt="" />
                                        </a>
                                    </div>
                                    <div class="col-md-9 col-sm-9 col-9">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(['/redaction/posts/view', 'id' => $post->post_id]); ?>" class="tab-post-link"><?php echo $post->title; ?></a>
                                        <small><?php echo DateHelper::formatDate($post->published_dt, false); ?></small>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <h3 class="hidden-xs-down fs-16 mb-20"><?php echo Yii::t('*', 'Теги'); ?></h3>
                <div class="hidden-xs-down mb-60">
                    <?php foreach ($tags as $tag) { ?>
                        <a class="tag" href="#">
                            <span class="txt"><?php echo $tag->title; ?></span>
                            <span class="num"><?php echo $tag->getPosts()->count(); ?></span>
                        </a>
                    <?php } ?>
                </div>
                <hr />
            </div>

            <div class="col-md-9">
                <?php /* @var $post common\modules\redaction\models\Post */ ?>
                <?php foreach ($dataProvider->getModels() as $post) { ?>
                    <div class="blog-post-item">
                        <figure class="mb-20">
                            <img class="img-fluid" src="<?php echo $post->getImageUrl('image', '870x490'); ?>" alt="">
                        </figure>
                        <h2><a href="<?php echo Yii::$app->urlManager->createUrl(['/redaction/posts/view', 'id' => $post->post_id]); ?>"><?php echo $post->title; ?></a></h2>
                        <ul class="blog-post-info list-inline">
                            <li>
                                <a href="#">
                                    <i class="fa fa-clock-o"></i>
                                    <span class="font-lato"><?php echo DateHelper::formatDate($post->published_dt, false); ?></span>
                                </a>
                            </li>
                            <?php if ($post->category) { ?>
                                <li>
                                    <i class="fa fa-folder-open-o"></i>
                                    <a class="category" href="#">
                                        <span class="font-lato"><?php echo $post->category->title; ?></span>
                                    </a>
                                </li>
                            <?php } ?>
                            <li>
                                <a href="#">
                                    <i class="fa fa-user"></i>
                                    <span class="font-lato"><?php echo $post->creator->profile->fullName; ?></span>
                                </a>
                            </li>
                        </ul>
                        <p><?php echo $post->lead; ?></p>
                        <a href="<?php echo Yii::$app->urlManager->createUrl(['/redaction/posts/view', 'id' => $post->post_id]); ?>" class="btn btn-reveal btn-default">
                            <i class="fa fa-plus"></i>
                            <span><?php echo Yii::t('*', 'Читать далее'); ?></span>
                        </a>
                    </div>
                <?php } ?>

                <ul class="pager">
                    <li class="previous"><a class="radius-0" href="#">&larr; <?php echo Yii::t('*', 'в прошлое'); ?></a></li>
                    <li class="next"><a class="radius-0" href="#"><?php echo Yii::t('*', 'в будущее'); ?> &rarr;</a></li>
                </ul>
            </div>

        </div>


    </div>
</section>
