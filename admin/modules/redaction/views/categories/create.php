<?php
/* @var $this     yii\web\View */
/* @var $category common\modules\redaction\models\Category */

$this->title = Yii::t('redaction', 'Создание новой категории');
$this->params['breadcrumbs'][] = ['label' => Yii::t('redaction', 'Категории'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
    <?php echo $this->render('blocks/_form', [
        'category' => $category
    ]); ?>
</div>
