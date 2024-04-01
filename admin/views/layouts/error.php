<?php
use yii\helpers\Html;

/* @var $this    yii\web\View */
/* @var $content string */

\admin\assets\ErrorAsset::register($this);
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
    <?php $this->registerLinkTag(['rel' => 'shortcut icon', 'href' => Yii::getAlias('@commonUrl/favicon/favicon.ico'), 'image/x-icon']); ?>
    <?php $this->head(); ?>
</head>
<body class="loading">
<?php $this->beginBody(); ?>
<div class="account-pages my-5">
    <div class="container">
        <?php echo $content; ?>
    </div>
</div>
<footer class="footer footer-alt">
    <?php echo implode(' - ', array_filter([2024, date('Y') != 2024 ? date('Y') : null])) ?> &#0169; <a href="<?php echo Yii::getAlias('@adminUrl'); ?>"><?php echo Yii::$app->name; ?></a>
</footer>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
