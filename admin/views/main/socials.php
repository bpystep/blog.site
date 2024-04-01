<?php
use admin\helpers\HtmlHelper;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\bootstrap4\ActiveForm;

/* @var $this     yii\web\View */
/* @var $settings common\models\Settings */
/* @var $socials  common\models\SettingsSocial[] */

$this->title = Yii::t('admin', 'Настройки: Социальные сети');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'Настройки'), 'url' => ['/main/settings']];
$this->params['breadcrumbs'][] = Yii::t('admin', 'Социальные сети');

$this->registerJsFile('/js/main/socials.js', ['depends' => \admin\assets\AdminAsset::class]);
?>

<?php echo $this->render('blocks/_tabs', [
    'tab' => 'socials'
]); ?>

<?php $form = ActiveForm::begin(['id' => 'settings-socials-form', 'enableAjaxValidation' => true, 'enableClientValidation' => false, 'validateOnBlur' => false]); ?>
<div class="card">
    <div class="card-body">
        <?php DynamicFormWidget::begin([
            'formId'          => 'settings-socials-form',
            'formFields'      => ['title', 'url', 'order'],
            'model'           => $socials[0],
            'widgetContainer' => 'js_socials_cont',
            'widgetBody'      => '.js-socials-list',
            'widgetItem'      => '.js-social-item',
            'insertButton'    => '.js-add-social',
            'deleteButton'    => '.js-delete-social'
        ]); ?>
        <ul class="list-group js-socials-list">
            <?php if (!empty($socials)) {
                foreach ($socials as $k => $social) {
                    echo $this->render('blocks/_social', [
                        'form'   => $form,
                        'k'      => $k,
                        'social' => $social
                    ]);
                }
            } ?>
        </ul>
        <?php echo HtmlHelper::button(Yii::t('admin', 'Добавить соц. сеть'), ['class' => 'btn btn-success mt-3 js-add-social'], 'fas fa-plus'); ?>
        <?php DynamicFormWidget::end(); ?>
    </div>
    <div class="card-footer d-flex justify-content-end">
        <div>
            <?php echo HtmlHelper::a(Yii::t('admin', 'Отменить'), Yii::$app->request->referrer, ['class' => 'btn btn-outline-secondary']); ?>
            <?php echo HtmlHelper::buttonSubmit(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary'], 'far fa-save', true); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
