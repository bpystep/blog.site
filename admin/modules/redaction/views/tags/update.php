<?php
/* @var $this yii\web\View */
/* @var $tag  common\modules\redaction\models\Tag */

$this->title = Yii::t('redaction', 'Редактирование тега: {0}', [$tag->title]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('redaction', 'Теги'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $tag->title;
$this->params['breadcrumbs'][] = Yii::t('redaction', 'Редактирование');
?>

<div class="card">
    <?php echo $this->render('blocks/_form', [
        'tag' => $tag
    ]); ?>
</div>
