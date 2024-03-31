<?php
use admin\helpers\HtmlHelper;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $tag  common\modules\redaction\models\Tag */
?>

<?php $form = ActiveForm::begin(['id' => 'tag-form', 'enableAjaxValidation' => true, 'enableClientValidation' => false, 'validateOnBlur' => false]); ?>
<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($tag, 'title', ['template' =>
                '{label}<div class="input-group"><span class="input-group-text">#</span>{input}{error}</div>'
            ])->textInput(['maxlength' => true]); ?>
        </div>
    </div>
</div>
<div class="card-footer d-flex">
    <div class="mr-auto">
        <?php if (!$tag->isNewRecord) {
            echo HtmlHelper::a(Yii::t('app', 'Удалить'), null, [
                'class'        => 'btn btn-danger js-sweet-alert',
                'title'        => Yii::t('app', 'Удалить'),
                'data'      => [
                    'bb-url'     => Yii::$app->urlManager->createUrl(['/redaction/tags/delete', 'id' => $tag->tag_id]),
                    'bb-icon'    => 'question',
                    'bb-title'   => Yii::t('app', 'Подтверждение'),
                    'bb-message' => Yii::t('redaction', 'Вы уверены, что хотите удалить тег?'),
                    'bb-confirm' => Yii::t('app', 'Да'),
                    'bb-method'  => 'post'
                ]
            ], 'fas fa-trash');
        } ?>
    </div>
    <div>
        <?php echo HtmlHelper::a(Yii::t('app', 'Отменить'), Yii::$app->request->referrer, ['class' => 'btn btn-outline-secondary'], false); ?>
        <?php echo HtmlHelper::buttonSubmit($tag->isNewRecord ? Yii::t('app', 'Создать') : Yii::t('app', 'Сохранить'), ['class' => $tag->isNewRecord ? 'btn btn-success' : 'btn btn-primary'], $tag->isNewRecord ? 'fas fa-check' : 'far fa-save', true); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

