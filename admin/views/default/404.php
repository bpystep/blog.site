<?php
/* @var $this      yii\web\View */
/* @var $exception Exception */

$this->title = Yii::t('error', 'Страница не найдена')
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-4">
        <div class="card">
            <div class="card-body p-4">
                <div class="error-ghost text-center">
                    <img src="/minton/images/error.svg" width="200" alt="<?php echo Yii::t('admin', 'Ошибка'); ?>">
                </div>
                <div class="text-center">
                    <h3 class="mt-4 text-uppercase fw-bold"><?php echo Yii::t('admin', 'Страница не найдена'); ?></h3>
                    <a class="btn btn-primary mt-3" href="<?php echo Yii::$app->urlManager->createUrl(['/main/index']); ?>">
                        <i class="mdi mdi-reply me-1"></i>
                        <?php echo Yii::t('admin', 'На главную'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
