<?php
/* @var $this yii\web\View */
?>

<div id="sidepanel" class="sidepanel-light">
    <a id="sidepanel_close" href="#">
        <i class="fa fa-remove"></i>
    </a>
    <div class="sidepanel-content">
        <h2 class="sidepanel-title"><?php echo Yii::t('*', 'Навигация'); ?></h2>
        <ul class="list-group">
            <li class="list-group-item">
                <a href="<?php echo Yii::$app->urlManager->createUrl(['/main/index']); ?>">
                    <i class="fa fa-home"></i><?php echo Yii::t('*', 'Главная'); ?>
                </a>
            </li>
            <li class="list-group-item">
                <a href="<?php echo Yii::$app->urlManager->createUrl(['/main/profile']); ?>">
                    <i class="fa fa-newspaper"></i><?php echo Yii::t('*', 'Обо мне'); ?>
                </a>
            </li>
            <li class="list-group-item">
                <a href="<?php echo Yii::$app->urlManager->createUrl(['/main/news']); ?>">
                    <i class="fa fa-newspaper"></i><?php echo Yii::t('*', 'Новости'); ?>
                </a>
            </li>
        </ul>
    </div>
</div>
