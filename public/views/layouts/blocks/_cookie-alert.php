<?php
/* @var $this yii\web\View */
?>

<div id="cookie-alert" data-expire="30" class="alert alert-info alert-position-bottom">
    <div class="container">
        <button type="button" class="close" data-dismiss="alert">
            <span class="cookie-close-btn" aria-hidden="true">×</span>
            <span class="sr-only"><?php echo Yii::t('*', 'Закрыть'); ?></span>
        </button>
        <p class="fs-13">
            <i class="fa fa-warning"></i>
            <?php echo Yii::t('*', 'Мы используем куки, чтобы предоставить вам лучший сервис. Продолжайте просмотр, если вы довольны этим.'); ?>
        </p>

    </div>
</div>
