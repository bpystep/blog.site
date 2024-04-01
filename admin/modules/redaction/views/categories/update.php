<?php
/* @var $this     yii\web\View */
/* @var $category common\modules\redaction\models\Category */

$this->title = Yii::t('redaction', 'Редактирование категории: {0}', [$category->title]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('redaction', 'Категории'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $category->title;
$this->params['breadcrumbs'][] = Yii::t('redaction', 'Редактирование');
?>

<div class="card">
    <?php echo $this->render('blocks/_form', [
        'category' => $category
    ]); ?>
</div>
