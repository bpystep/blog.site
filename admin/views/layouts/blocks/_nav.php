<?php
use admin\components\Menu;
use common\modules\user\models\User;
use yii\helpers\ArrayHelper;

/* @var $this        yii\web\View */
/* @var $user        User */
/* @var $controller  admin\controllers\DefaultController */

$controller = $this->context;
$module = $controller->module;

$isUserModule      = $module->id == 'user';
$isRedactionModule = $module->id == 'redaction';

$items = [];

if ($user->isAdmin) {
    $items[] = '<li class="menu-title">' . Yii::t('menu', 'Сайт') . '</li>';
    $items[] = [
        'label'  => Yii::t('menu', 'Аккаунт'),
        'url'    => ['/user/crud/update'],
        'icon'   => 'fas fa-user',
        'active' => $isUserModule
    ];
    $items[] = [
        'label' => Yii::t('menu', 'Настройки'),
        'icon'  => 'fe-settings',
        'url'   => ['/main/settings']
    ];
    $items[] = '<li class="menu-title mt-2">' . Yii::t('menu', 'Редакция') . '</li>';
    $items[] = [
        'label'  => Yii::t('menu', 'Новости'),
        'url'    => ['/redaction/posts/index'],
        'icon'   => 'fas fa-newspaper'
    ];
    $items[] = [
        'label'  => Yii::t('menu', 'Категории'),
        'url'    => ['/redaction/categories/index'],
        'icon'   => 'fas fa-folder'
    ];
    $items[] = [
        'label'  => Yii::t('menu', 'Теги'),
        'url'    => ['/redaction/tags/index'],
        'icon'   => 'fas fa-tags'
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
