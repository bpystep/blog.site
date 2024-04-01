<?php
use admin\helpers\HtmlHelper;
use yii\bootstrap4\ActiveForm;

/* @var $this     yii\web\View */
/* @var $category common\modules\redaction\models\Category */
?>

<?php $form = ActiveForm::begin(['id' => 'category-form', 'enableAjaxValidation' => true, 'enableClientValidation' => false, 'validateOnBlur' => false]); ?>
<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($category, 'title')->textInput(['maxlength' => true]); ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php echo $form->field($category, 'rank')->input('number', ['min' => 1, 'style' => 'width: 130px;']); ?>
            </div>
        </div>
    </div>
</div>
<div class="card-footer d-flex">
    <div class="mr-auto">
        <?php if (!$category->isNewRecord) {
            echo HtmlHelper::a(Yii::t('app', 'Удалить'), null, [
                'class'        => 'btn btn-danger js-sweet-alert',
                'title'        => Yii::t('app', 'Удалить'),
                'data'      => [
                    'bb-url'     => Yii::$app->urlManager->createUrl(['/redaction/categories/delete', 'id' => $category->category_id]),
                    'bb-icon'    => 'question',
                    'bb-title'   => Yii::t('app', 'Подтверждение'),
                    'bb-message' => Yii::t('redaction', 'Вы уверены, что хотите удалить категорию?'),
                    'bb-confirm' => Yii::t('app', 'Да'),
                    'bb-method'  => 'post'
                ]
            ], 'fas fa-trash');
        } ?>
    </div>
    <div>
        <?php echo HtmlHelper::a(Yii::t('app', 'Отменить'), Yii::$app->request->referrer, ['class' => 'btn btn-outline-secondary'], false); ?>
        <?php echo HtmlHelper::buttonSubmit($category->isNewRecord ? Yii::t('app', 'Создать') : Yii::t('app', 'Сохранить'), ['class' => $category->isNewRecord ? 'btn btn-success' : 'btn btn-primary'], $category->isNewRecord ? 'fas fa-check' : 'far fa-save', true); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

