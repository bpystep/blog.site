<?php
use admin\components\DateTimePicker;
use admin\helpers\HtmlHelper;
use admin\widgets\Cropper\Widget as Cropper;
use admin\widgets\Imperavi\Widget as Imperavi;
use common\modules\redaction\models\Post;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this       yii\web\View */
/* @var $post       common\modules\redaction\models\Post */
/* @var $categories common\modules\redaction\models\Category[] */
/* @var $tags       common\modules\redaction\models\Tag[] */

$this->title = $post->is_temp ? Yii::t('redaction', 'Создание новой новости') : Yii::t('redaction', 'Редактирование новости: {0}', [$post->title]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('redaction', 'Новости'), 'url' => ['index']];
if ($post->is_temp) {
    $this->params['breadcrumbs'][] = $this->title;
} else {
    $this->params['breadcrumbs'][] = ['label' => "#{$post->post_id}"];
    $this->params['breadcrumbs'][] = Yii::t('redaction', 'Редактирование');
}
?>

<div class="card">
    <?php $form = ActiveForm::begin(['id' => 'category-form', 'enableAjaxValidation' => true, 'enableClientValidation' => false, 'validateOnBlur' => false]); ?>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-3">
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo Cropper::widget([
                            'model'        => $post,
                            'attribute'    => 'image',
                            'ratio'        => 1.775,
                            'previewWidth' => '100%',
                            'label'        => Yii::t('build', 'Изменить картинку')
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo $form->field($post, 'category_id')->dropDownList(ArrayHelper::map($categories, 'category_id', 'title'), [
                            'prompt' => Yii::t('redaction', 'Выберите категорию'),
                            'style'  => 'width: 100%;'
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo $form->field($post, 'is_public')->dropDownList(Post::getPublishStatusLabels(), [
                            'style' => 'width: 100%;'
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo $form->field($post, 'published_dt', [
                            'template' => '{label}<div class="input-group"><span class="input-group-text"><i class="far fa-clock"></i></span>{input}{hint}{error}</div>'
                        ])->widget(DateTimePicker::class, [
                            'template'      => '{input}{addon}{error}',
                            'options'        => [
                                'value'    => Yii::$app->formatter->asDate($post->publishedDt, 'dd.MM.yyyy, HH:mm'),
                                'style'    => 'background: #ffffff;'
                            ],
                            'language'       => mb_substr(Yii::$app->language, 0, 2),
                            'pickButtonIcon' => 'far fa-clock',
                            'clientOptions'  => [
                                'startView' => 1,
                                'autoclose' => true,
                                'format'    => 'dd.mm.yyyy, HH:ii',
                                'todayBtn'  => true
                            ]
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo HtmlHelper::checkboxForm($form, $post, 'on_main'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo HtmlHelper::checkboxForm($form, $post, 'in_slider'); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo $form->field($post, 'title')->textInput(['maxlength' => true]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo $form->field($post, 'lead')->textInput(['maxlength' => true]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo $form->field($post, 'tagValues')->dropDownList(ArrayHelper::map($tags, 'tag_id', 'title'), [
                            'multiple'         => true,
                            'data-placeholder' => Yii::t('redaction', 'Выберите теги'),
                            'style'            => 'width: 100%;'
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo $form->field($post, 'text')->widget(Imperavi::class, [
                            'settings' => [
                                'imageUpload'  => Yii::$app->urlManager->createUrl(['/redaction/posts/image-upload']),
                                'fileUpload'   => Yii::$app->urlManager->createUrl(['/redaction/posts/file-upload']),
                                'imagemanager' => Yii::$app->urlManager->createUrl(['/redaction/posts/get-images'])
                            ]
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer d-flex">
        <div class="mr-auto">
            <?php if (!$post->is_temp) {
                echo HtmlHelper::a(Yii::t('app', 'Удалить'), null, [
                    'class'        => 'btn btn-danger js-sweet-alert',
                    'title'        => Yii::t('app', 'Удалить'),
                    'data'      => [
                        'bb-url'     => Yii::$app->urlManager->createUrl(['/redaction/posts/delete', 'id' => $post->post_id]),
                        'bb-icon'    => 'question',
                        'bb-title'   => Yii::t('app', 'Подтверждение'),
                        'bb-message' => Yii::t('redaction', 'Вы уверены, что хотите удалить новость?'),
                        'bb-confirm' => Yii::t('app', 'Да'),
                        'bb-method'  => 'post'
                    ]
                ], 'fas fa-trash');
            } ?>
        </div>
        <div>
            <?php echo HtmlHelper::a(Yii::t('app', 'Отменить'), Yii::$app->request->referrer, ['class' => 'btn btn-outline-secondary'], false); ?>
            <?php echo HtmlHelper::buttonSubmit($post->is_temp ? Yii::t('app', 'Создать') : Yii::t('app', 'Сохранить'), ['class' => $post->is_temp ? 'btn btn-success' : 'btn btn-primary'], $post->is_temp ? 'fas fa-check' : 'far fa-save', true); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
