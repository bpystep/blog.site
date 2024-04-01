<?php
use public\assets\SmartyAsset;

/* @var $this        yii\web\View */
/* @var $transparent boolean */
?>

<div id="header" class="navbar-toggleable-md sticky <?php echo $transparent ? 'transparent dark' : 'shadow-after-3'; ?> clearfix">
    <header id="topNav">
        <div class="container">
            <button class="btn btn-mobile" data-toggle="collapse" data-target=".nav-main-collapse">
                <i class="fa fa-bars"></i>
            </button>
            <a class="logo float-left" href="<?php echo Yii::$app->urlManager->createUrl(['/main/index']); ?>">
                <img src="/images/logo.png">
            </a>
            <div class="navbar-collapse collapse float-right nav-main-collapse <?php if (SmartyAsset::$isDark) echo 'submenu-dark'; ?>">
                <?php echo $this->render('_menu'); ?>
            </div>
        </div>
    </header>
</div>
