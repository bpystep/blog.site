<?php
/* @var $this       yii\web\View */
/* @var $content    string */
/* @var $controller public\controllers\DefaultController */

$controller = $this->context;
?>

<?php $this->beginContent('@public/views/layouts/default.php'); ?>

    <div id="wrapper">
        <?php echo $this->render('blocks/_header-vertical', ['transparent'  => $controller->transparentHeader]); ?>
        <?php echo $content; ?>
    </div>
<?php $this->endContent(); ?>
