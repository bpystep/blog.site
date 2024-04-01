<?php

use public\assets\SmartyAsset;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $content string */
/* @var $controller public\controllers\DefaultController */

Yii::$app->seo->registerMeta($this);

\public\assets\PublicAsset::register($this);
\public\helpers\SnackbarHelper::register($this);

$this->registerJs("var plugin_path = '" . Yii::getAlias('@publicUrl/smarty/plugins/') . "';", \yii\web\View::POS_HEAD);
$this->registerJs("var commonDomain = '" . Yii::getAlias('@commonUrl') . "';", \yii\web\View::POS_HEAD);

$controller = $this->context;
?>

<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language; ?>">
<head>
    <meta charset="<?php echo Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i|Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i|Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&amp;subset=cyrillic" rel="stylesheet">
    <?php echo Html::csrfMetaTags(); ?>
    <?php $this->registerLinkTag(['rel' => 'yandex-tableau-widget', 'href' => Yii::getAlias('@commonUrl/manifest.json')]); ?>
    <?php $this->registerLinkTag(['rel' => 'shortcut icon', 'href' => Yii::getAlias('@commonUrl/favicon/favicon.ico'), 'image/x-icon']); ?>
    <title><?php echo Html::encode($this->title); ?></title>
    <?php $this->head(); ?>
</head>
<body class="smoothscroll enable-animation <?php if (SmartyAsset::$isDark) echo 'dark'; ?> <?php if (SmartyAsset::$isBoxed) echo implode(' ' , ['boxed', 'pattern1']); ?>">
<?php $this->beginBody(); ?>
    <?php echo $content; ?>
    <a href="#" id="toTop"></a>
    <div id="preloader"><div class="inner"><span class="loader"></span></div></div>
    <?php echo $this->render('blocks/_cookie-alert'); ?>
    <?php if (isset($this->blocks['footer'])) {
        echo $this->blocks['footer'];
    } ?>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
