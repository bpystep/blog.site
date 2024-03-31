<?php
/* @var $this yii\web\View */
/* @var $user common\modules\user\models\User */
?>

<div class="navbar-custom">
    <div class="container-fluid">
        <ul class="list-unstyled topnav-menu float-end mb-0">
            <li class="dropdown topbar-dropdown">
                <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="<?php echo $user->profile->getImageUrl('photo', '200x200'); ?>" alt="<?php echo $user->profile->fullName; ?>" class="rounded-circle">
                    <span class="pro-user-name ms-1">
                        <?php echo $user->profile->fullName; ?> <i class="mdi mdi-chevron-down"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                    <a href="<?php echo Yii::$app->urlManager->createUrl(['/user/crud/update']); ?>" class="dropdown-item notify-item" target="_blank">
                        <i class="ri-account-circle-line"></i>
                        <span><?php echo Yii::t('user', 'Аккаунт'); ?></span>
                    </a>
                    <!--<a href="#" class="dropdown-item notify-item">
                        <i class="ri-settings-3-line"></i>
                        <span><?php /*echo Yii::t('user', 'Настройки'); */?></span>
                    </a>-->
                    <div class="dropdown-divider"></div>
                    <a href="<?php echo Yii::$app->urlManagerPublic->createUrl(['/user/security/logout']); ?>" class="dropdown-item notify-item" data-method="post">
                        <i class="ri-logout-box-line"></i>
                        <span><?php echo Yii::t('user', 'Выйти'); ?></span>
                    </a>
                </div>
            </li>
            <li class="dropdown d-none d-lg-inline-block">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen" href="#">
                    <i class="fe-maximize noti-icon"></i>
                </a>
            </li>
        </ul>
        <?php echo $this->render('_logo'); ?>
        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
            <li>
                <button class="button-menu-mobile waves-effect waves-light"><i class="fe-menu"></i></button>
            </li>
            <li>
                <a class="navbar-toggle nav-link" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                    <div class="lines"><span></span><span></span><span></span></div>
                </a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
</div>

