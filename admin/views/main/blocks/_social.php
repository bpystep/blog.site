<?php
use common\models\SettingsSocial;
use yii\helpers\Html;
use admin\helpers\HtmlHelper;

/* @var $this         yii\web\View */
/* @var $form         yii\bootstrap4\ActiveForm */
/* @var $k            integer */
/* @var $social       common\models\SettingsSocial */
/* @var $socialLabels [] */
?>
<li class="list-group-item js-social-item">
    <?php if (!$social->isNewRecord) {
        echo Html::activeHiddenInput($social, "[{$k}]social_id");
    } ?>
    <?php echo Html::activeHiddenInput($social, "[{$k}]settings_id"); ?>
    <div class="row">
        <div class="col-md-4">
            <?php echo $form->field($social, "[{$k}]social")->label(false)->dropDownList(SettingsSocial::$titleLabels, [
                'prompt' => Yii::t('admin', 'Соц. сеть')
            ]); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->field($social, "[{$k}]url")->label(false)->textInput([
                'placeholder' => $social->getAttributeLabel('url')
            ]); ?>
        </div>
        <div class="col-md-2">
            <?php echo $form->field($social, "[{$k}]rank")->label(false)->input('number', [
                'placeholder' => $social->getAttributeLabel('rank')
            ]); ?>
        </div>
        <div class="col-md-1">
            <?php echo HtmlHelper::button('<i class="fas fa-trash"></i>', ['class' => 'btn btn-danger add-tooltip js-delete-social', 'title' => Yii::t('admin', 'Удалить')]); ?>
        </div>
    </div>
</li>
