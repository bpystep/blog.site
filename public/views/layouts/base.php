<?php

use yii\helpers\Html;

/* @var $this    yii\web\View */
/* @var $content string */
?>

<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language; ?>">
<head>
    <meta charset="<?= Yii::$app->charset; ?>">
    <title><?= Html::encode($this->title); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <?= Html::csrfMetaTags(); ?>
    <?php $this->head(); ?>
</head>
<body class="loading">
<?php $this->beginBody(); ?>
<?= $content; ?>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
