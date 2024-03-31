<?php
use admin\helpers\HtmlHelper;
use yii\bootstrap4\ActiveForm;

/* @var $this     yii\web\View */
/* @var $settings common\models\Settings */

$this->title = Yii::t('admin', 'Настройки');
$this->params['breadcrumbs'][] = Yii::t('admin', 'Настройки');

rmrevin\yii\fontawesome\AssetBundle::register($this);
$this->registerJs("$(document).ready(function() {
    $('#settings-slidetop_block1_icon, #settings-slidetop_block2_icon').select2({
        templateSelection: formatIcons,
        templateResult: formatIcons
    });
    function formatIcons(icon) {
        var el = $('<span>').css({'display': 'flex', 'alignItems': 'center'}).html($(icon.element).text());
        $(el).find('.fa').addClass('fa-2x').css({'width': '60px', 'textAlign': 'center'}).after(
            $('<span>').text($($(icon)[0].element).val()
        ));
        return el;
    }
});");
?>

<?php echo $this->render('blocks/_tabs', [
    'tab' => 'default'
]); ?>

<div class="card">
    <?php $form = ActiveForm::begin(['id' => 'settings-form', 'enableAjaxValidation' => true, 'enableClientValidation' => false, 'validateOnBlur' => false]); ?>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                Тут могут быть какие-то настройки сайта
            </div>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-end">
        <div>
            <?php echo HtmlHelper::a(Yii::t('admin', 'Отменить'), Yii::$app->request->referrer, ['class' => 'btn btn-outline-secondary']); ?>
            <?php echo HtmlHelper::buttonSubmit(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary'], 'far fa-save', true); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
