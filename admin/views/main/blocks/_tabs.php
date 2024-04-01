<?php
/* @var $this yii\web\View */
/* @var $tab  string */
?>
<ul class="nav nav-pills navtab-bg nav-justified mb-2">
    <li class="nav-item">
        <a href="<?php echo Yii::$app->urlManager->createUrl(['/main/settings']); ?>" class="nav-link <?php if ($tab == 'default') echo 'active'; ?>">
            <?php echo Yii::t('admin', 'Основное'); ?>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?php echo Yii::$app->urlManager->createUrl(['/main/socials']); ?>" class="nav-link <?php if ($tab == 'socials') echo 'active'; ?>">
            <?php echo Yii::t('admin', 'Соц. сети'); ?>
        </a>
    </li>
</ul>
