<?php
/* @var $this       yii\web\View */
/* @var $controller yii\web\Controller */

$controller = $this->context;
$module = $controller->module;
$action = $controller->action;
?>

<li <?php if ($module->id == 'public' && $controller->id == 'main' && $action->id == 'index') echo 'class="active"'; ?>>
    <a href="<?php echo Yii::$app->urlManager->createUrl(['/main/index']); ?>">
        <?php echo Yii::t('*', 'Главная'); ?>
    </a>
</li>
<li <?php if ($module->id == 'public' && $controller->id == 'main' && $action->id == 'profile') echo 'class="active"'; ?>>
    <a href="<?php echo Yii::$app->urlManager->createUrl(['/main/profile']); ?>">
        <?php echo Yii::t('*', 'Обо мне'); ?>
    </a>
</li>
<li <?php if ($module->id == 'public' && $controller->id == 'main' && $action->id == 'posts') echo 'class="active"'; ?>>
    <a href="<?php echo Yii::$app->urlManager->createUrl(['/main/news']); ?>">
        <?php echo Yii::t('*', 'Новости'); ?>
    </a>
</li>
<!--<li>
    <a id="sidepanel_btn" href="#" class="fa fa-bars"></a>
</li>-->
