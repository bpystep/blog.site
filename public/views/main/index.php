<?php
use common\helpers\DateHelper;
use yii\helpers\Html;

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
        <div class="text-center">
            <h1 class="mb-0"><?php echo Yii::t('*', 'Добро пожаловать на сайт {0}', [Html::tag('span', Yii::$app->name)]); ?></h1>
            <h2 class="col-sm-10 offset-sm-1 mb-0 fw-300 text-muted fs-20"><?php echo Yii::t('*', 'Лучшие новости в мире!'); ?></h2>
        </div>
    </div>
</section>

<?php if (!empty($slides)) { ?>
    <section>
        <div class="container">
            <div class="flexslider" style="background: transparent; border: none;">
                <ul class="slides">
                    <?php foreach ($slides as $post) { ?>
                        <li>
                            <a href="<?php echo Yii::$app->urlManager->createUrl(['/redaction/posts/view', 'id' => $post->post_id]); ?>" target="_blank">
                                <img src="<?php echo $post->getImageUrl('image', '870x490'); ?>" width="870" height="490" alt="<?php echo $post->title; ?>">
                                <div class="flex-caption"><?php echo $post->title; ?></div>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </section>
<?php } ?>

<?php if (!empty($posts)) { ?>
    <section>
        <div class="container">
            <div class="row">
                <?php foreach ($posts as $post) { ?>
                    <div class="blog-post-item col-md-4 col-sm-4">
                        <figure class="mb-20">
                            <img class="img-fluid" src="<?php echo $post->getImageUrl('image', '870x490'); ?>" alt="<?php echo $post->title; ?>">
                        </figure>
                        <h2><a href="<?php echo Yii::$app->urlManager->createUrl(['/redaction/posts/view', 'id' => $post->post_id]); ?>"><?php echo $post->title; ?></a></h2>
                        <ul class="blog-post-info list-inline">
                            <li>
                                <a href="#">
                                    <i class="fa fa-clock-o"></i>
                                    <span class="font-lato"><?php echo DateHelper::formatDate($post->published_dt, false); ?></span>
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
            </div>
        </div>
    </section>
<?php } ?>

<section>
    <div class="container">
        <div class="text-center">
            <h1 class="mb-0"><?php echo Yii::t('*', 'Вы пролистали все новости на сайте {0}', [Html::tag('span', Yii::$app->name)]); ?></h1>
            <h2 class="col-sm-10 offset-sm-1 mb-0 fw-300 text-muted fs-20"><?php echo Yii::t('*', 'Хорошего дня!'); ?></h2>
        </div>
    </div>
</section>

