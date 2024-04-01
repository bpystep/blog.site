<?php
/* @var $this     yii\web\View */
/* @var $user     common\modules\user\models\User */
/* @var $module   common\modules\user\Module */
/* @var $roles    [] */

$this->title = Yii::t('user', 'Изменить аккаунт');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'Пользователи')];
$this->params['breadcrumbs'][] = $user->profile->fullName;
$this->params['breadcrumbs'][] = Yii::t('user', 'Редактирование');
?>

<div class="card">
    <?php echo $this->render('blocks/_form', [
        'user'   => $user,
        'module' => $module,
        'roles'  => $roles
    ]); ?>
</div>
