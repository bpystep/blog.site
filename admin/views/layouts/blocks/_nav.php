<?php
use admin\components\Menu;
use common\modules\user\models\User;
use yii\helpers\ArrayHelper;

/* @var $this        yii\web\View */
/* @var $user        User */
/* @var $controller  admin\controllers\DefaultController */

$controller = $this->context;

$isUserModule = $controller->module->id == 'user';

$items = [];

if ($user->isAdmin) {
    $items[] = [
        'label' => Yii::t('menu', 'Настройки'),
        'icon'  => 'fe-settings',
        'url'   => ['/main/settings']
    ];
    $items[] = [
        'label'  => Yii::t('menu', 'Аккаунт'),
        'url'    => ['/user/crud/update'],
        'icon'   => 'fas fa-user',
        'active' => $isUserModule
    ];
}
?>

<div class="left-side-menu">
    <?php echo $this->render('_logo'); ?>
    <div class="h-100" data-simplebar>
        <div id="sidebar-menu">
            <?php echo Menu::widget(['items' => $items]); ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
