<?php
use public\assets\SmartyAsset;

/* @var $this        yii\web\View */
/* @var $user        common\modules\user\models\User */
/* @var $transparent boolean */
/* @var $controller  public\controllers\DefaultController */

$controller = $this->context;
$settings = $controller->settings;
?>

<div id="mainMenu" class="navbar-toggleable-md sidebar-vertical <?php if (SmartyAsset::$isDark) echo 'sidebar-dark'; ?>">
    <div class="sidebar-nav">
        <div class="navbar navbar-default" role="navigation">
            <a href="<?php echo Yii::$app->urlManager->createUrl(['/main/index']); ?>" class="logo text-center ml-10">
                <img src="/images/logo.png">
            </a>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse sidebar-navbar-collapse collapse">
                <?php echo $this->render('_menu-vertical'); ?>
            </div>
        </div>
    </div>
    <?php if (!empty($settings->socials)) { ?>
        <p class="text-center hidden-sm-down fs-15">
            <?php foreach ($settings->socials as $social) { ?>
                <a href="<?php echo $social->urlHtml; ?>" target="_blank"><?php echo $social->url; ?></a>
                <br>
            <?php } ?>
        </p>
    <?php } ?>
</div>
