<?php
use yii\helpers\Html;

/* @var $this    yii\web\View */
/* @var $content string */

\admin\assets\AdminAsset::register($this);
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language; ?>">
<head>
    <meta charset="<?php echo Yii::$app->charset; ?>">
    <title><?php echo Html::encode($this->title); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <?php echo Html::csrfMetaTags(); ?>
    <?php $this->registerLinkTag(['rel' => 'yandex-tableau-widget', 'href' => Yii::getAlias('@commonUrl/manifest.json')]); ?>
    <?php $this->registerLinkTag(['rel' => 'shortcut icon', 'href' => Yii::getAlias('@commonUrl/favicon/favicon.ico'), 'image/x-icon']); ?>
    <?php $this->head(); ?>
</head>
<body class="loading">
<?php $this->beginBody(); ?>
    <?php echo $content; ?>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
