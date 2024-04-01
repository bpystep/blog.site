<?php
use common\helpers\DateHelper;

/* @var $this       yii\web\View */
/* @var $controller public\controllers\MainController */
/* @var $slides     common\modules\redaction\models\Post[] */
/* @var $posts      common\modules\redaction\models\Post[] */

$this->title = Yii::$app->name;
Yii::$app->seo->setMeta($this);

$controller = $this->context;
?>

<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-4">
                <div class="thumbnail text-center">
                    <img class="img-fluid" src="/images/8-min.jpg" alt="" />
                    <h2 class="fs-18 mt-10 mb-0"><?php echo Yii::t('*', 'Елизавета Волкова'); ?></h2>
                    <h3 class="fs-11 mt-0 mb-10 text-muted"><?php echo Yii::t('*', 'писательница и журналистка'); ?></h3>
                </div>
                <div class="box-light mb-30">
                    <div class="row mb-20">
                        <div class="col-md-4 col-sm-4 col-4 text-center bold">
                            <h2 class="fs-30 mt-10 mb-0 font-raleway">12</h2>
                            <h3 class="fs-11 mt-0 mb-10 text-info"><?php echo Yii::t('*', 'Статей'); ?></h3>
                        </div>
                        <div class="col-md-4 col-sm-4 col-4 text-center bold">
                            <h2 class="fs-30 mt-10 mb-0 font-raleway">34</h2>
                            <h3 class="fs-11 mt-0 mb-10 text-info"><?php echo Yii::t('*', 'Публикаций'); ?></h3>
                        </div>

                        <div class="col-md-4 col-sm-4 col-4 text-center bold">
                            <h2 class="fs-30 mt-10 mb-0 font-raleway">32</h2>
                            <h3 class="fs-11 mt-0 mb-10 text-info"><?php echo Yii::t('*', 'Грамот'); ?></h3>
                        </div>
                    </div>

                    <div class="text-muted">
                        <h2 class="fs-18 text-muted mb-6"><b><?php echo Yii::t('*', 'Об'); ?></b> <?php echo Yii::t('*', 'Елизавете'); ?></h2>
                        <p><?php echo Yii::t('*', 'Лучшая писательница и журналистка'); ?></p>
                        <ul class="list-unstyled m-0">
                            <li class="mb-10"><i class="fa fa-globe fw-20 hidden-xs-down hidden-sm"></i>
                                <a href="#"><?php echo Yii::t('*', 'Личный блог'); ?></a>
                            </li>
                            <li class="mb-10"><i class="fa fa-facebook fw-20 hidden-xs-down hidden-sm"></i>
                                <a href="#"><?php echo Yii::t('*', 'ВКонтакте'); ?></a>
                            </li>
                            <li class="mb-10"><i class="fa fa-twitter fw-20 hidden-xs-down hidden-sm"></i>
                                <a href="#"><?php echo Yii::t('*', 'Instagram'); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-8">
                <div class="box-flip box-icon box-icon-center box-icon-round box-icon-large text-center m-0">
                    <div class="front">
                        <div class="box1 rad-0">
                            <div class="box-icon-title">
                                <i class="fa fa-tasks"></i>
                                <h2><?php echo Yii::t('*', 'Елизавета Волкова'); ?> &ndash; <?php echo Yii::t('*', 'Миссия'); ?></h2>
                            </div>
                            <p>Nullam id dolor id nibh ultricies vehicula ut id elit. Integer posuere erat a ante venenatis dapibus posuere</p>
                        </div>
                    </div>

                    <div class="back">
                        <div class="box2 rad-0">
                            <h4><?php echo Yii::t('*', 'Кто я?'); ?></h4>
                            <hr />
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque.</p>
                        </div>
                    </div>
                </div>

                <div class="box-light">
                    <?php if (!empty($slides)) { ?>
                        <div class="box-inner">
                            <div class="owl-carousel buttons-autohide controlls-over m-0" data-plugin-options='{"singleItem": false, "items":"1", "autoPlay": 3500, "navigation": true, "pagination": false}'>
                                <?php foreach ($slides as $post) { ?>
                                    <div class="img-hover" href="#">
                                        <a href="<?php echo Yii::$app->urlManager->createUrl(['/redaction/posts/views', 'id' => $post->post_id]); ?>">
                                            <img class="img-fluid" src="<?php echo $post->getImageUrl('image', '870x490'); ?>" alt="<?php echo $post->title; ?>">
                                        </a>
                                        <h2 class="text-left mt-20 bold fs-16 elipsis"><a href="<?php echo Yii::$app->urlManager->createUrl(['/redaction/posts/views', 'id' => $post->post_id]); ?>"><?php echo $post->title; ?></a></h2>
                                        <p class="text-left"><?php echo $post->lead; ?></p>
                                        <ul class="text-left fs-12 list-inline list-separator m-0">
                                            <li>
                                                <i class="fa fa-calendar"></i>
                                                <?php echo DateHelper::formatDate($post->published_dt, false, false, false, true); ?>
                                            </li>
                                        </ul>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (!empty($posts)) { ?>
                        <div class="row">
                            <?php foreach ($posts as $post) { ?>
                                <div class="col-md-6 col-sm-6">
                                    <div class="box-inner mt-30">
                                        <a href="#">
                                            <img class="img-fluid" src="<?php echo $post->getImageUrl('image', '870x490'); ?>" alt="<?php echo $post->title; ?>" />
                                        </a>
                                        <h3 class="text-left mt-20 bold fs-16 elipsis"><a href="<?php echo Yii::$app->urlManager->createUrl(['/redaction/posts/views', 'id' => $post->post_id]); ?>"><?php echo $post->title; ?></a></h3>
                                        <p class="text-left"><?php echo $post->lead; ?></p>
                                        <ul class="text-left fs-12 list-inline list-separator m-0">
                                            <li>
                                                <i class="fa fa-calendar"></i>
                                                <?php echo DateHelper::formatDate($post->published_dt, false, false, false, true); ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
