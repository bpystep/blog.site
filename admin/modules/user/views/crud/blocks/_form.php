<?php
use admin\helpers\HtmlHelper;
use yii\bootstrap4\ActiveForm;

/* @var $this    yii\web\View */
/* @var $user    common\modules\user\models\User */
/* @var $profile common\modules\user\models\UserProfile */
/* @var $module  common\modules\user\Module */
/* @var $roles   [] */

$profile = $user->profile;
?>

<style>
    .select2-selection.select2-selection--single {
        border-radius: 0;
    }
</style>

<?php $form = ActiveForm::begin(['id' => 'user-form', 'enableAjaxValidation' => true, 'enableClientValidation' => false, 'validateOnBlur' => false]); ?>
<div class="card-body">
    <div class="row">
        <div class="col-md-3">
            <?php echo $form->field($user, 'username')->textInput([
                'disabled' => true
            ]); ?>
        </div>
        <div class="col-md-3">
            <?php echo $form->field($user, 'email')->textInput(); ?>
        </div>
        <div class="col-md-3">
            <?php echo $form->field($user, 'password')->passwordInput(); ?>
        </div>
    </div>
    <?php if (!$user->isNewRecord) { ?>
        <div class="row">
            <div class="col-md-6">
                <?php echo $form->field($profile, 'first_name')->textInput(); ?>
            </div>
            <div class="col-md-6">
                <?php echo $form->field($profile, 'last_name')->textInput(); ?>
            </div>
        </div>
    <?php } ?>
</div>
<div class="card-footer d-flex justify-content-end">
    <div>
        <?php echo HtmlHelper::a(Yii::t('admin', 'Отменить'), Yii::$app->request->referrer, ['class' => 'btn btn-outline-secondary'], false); ?>
        <?php echo HtmlHelper::buttonSubmit($user->isNewRecord ? Yii::t('common', 'Создать') : Yii::t('common', 'Сохранить'), ['class' => $user->isNewRecord ? 'btn btn-success' : 'btn btn-primary'], $user->isNewRecord ? 'fas fa-check' : 'far fa-save', true); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
