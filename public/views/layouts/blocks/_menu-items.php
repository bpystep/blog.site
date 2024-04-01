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
<li <?php if ($module->id == 'redaction' && $controller->id == 'posts') echo 'class="active"'; ?>>
    <a href="<?php echo Yii::$app->urlManager->createUrl(['/redaction/posts/index']); ?>">
        <?php echo Yii::t('*', 'Новости'); ?>
    </a>
</li>
<!--<li>
    <a id="sidepanel_btn" href="#" class="fa fa-bars"></a>
</li>-->
