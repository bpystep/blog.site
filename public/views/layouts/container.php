<?php

use public\widgets\PageHeader\Widget as PageHeader;

/* @var $this    yii\web\View */
/* @var $content string */
?>

<?php $this->beginContent('@public/views/layouts/base.php'); ?>
    <?php if (isset($this->blocks['header'])) {
        echo PageHeader::widget(['content' => $this->blocks['header']]);
    } ?>
    <?php echo $content; ?>
<?php $this->endContent(); ?>
