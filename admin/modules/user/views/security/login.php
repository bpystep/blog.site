<?php
use admin\helpers\HtmlHelper;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this  yii\web\View*/
/* @var $form  ActiveForm */
/* @var $model admin\modules\user\forms\LoginForm */
?>

<div class="account-pages mt-5 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-4">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="text-center w-75 m-auto">
                            <h3><?php echo Yii::t('user', 'Авторизация'); ?></h3>
                            <p class="text-muted mb-4 mt-3"><?php echo Yii::t('user', 'Введите логин и пароль'); ?></p>
                        </div>
                        <?php $form = ActiveForm::begin([
                            'enableAjaxValidation' => true,
                            'action'               => Yii::$app->urlManager->createUrl(['/user/security/login']),
                            'options'              => ['autocomplete' => 'off']
                        ]); ?>
                            <div class="mb-2">
                                <?php echo $form->field($model, 'login')->textInput(['maxlength' => true]); ?>
                            </div>
                            <div class="mb-2">
                                <?php echo $form->field($model, 'password')->passwordInput(['autocomplete' => 'current-password']); ?>
                            </div>
                            <div class="mb-3">
                                <?php echo HtmlHelper::checkboxForm($form, $model, 'rememberMe'); ?>
                            </div>
                            <div class="d-grid mb-0 text-center">
                                <?php echo Html::submitButton(Yii::t('user', 'Войти'), ['class' => 'btn btn-primary']); ?>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
