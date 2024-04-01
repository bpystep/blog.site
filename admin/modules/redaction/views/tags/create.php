<?php
/* @var $this yii\web\View */
/* @var $tag  common\modules\redaction\models\Tag */

$this->title = Yii::t('redaction', 'Создание нового тега');
$this->params['breadcrumbs'][] = ['label' => Yii::t('redaction', 'Теги'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
    <?php echo $this->render('blocks/_form', [
        'tag' => $tag
    ]); ?>
</div>
