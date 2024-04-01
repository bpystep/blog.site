<?php

/* @var $this       yii\web\View */
/* @var $content    string */
/* @var $controller public\controllers\DefaultController */

$controller = $this->context;
?>

<?php $this->beginContent('@public/views/layouts/default.php'); ?>
    <div id="wrapper">
        <?php echo $this->render('blocks/_header', ['transparent'  => $controller->transparentHeader]); ?>
        <?php echo $content; ?>
        <?php echo $this->render('blocks/_footer'); ?>
    </div>
    <?php //echo $this->render('blocks/_sidepanel'); ?>
    <?php if (isset($this->blocks['footer'])) {
        echo $this->blocks['footer'];
    } ?>
<?php $this->endContent(); ?>
