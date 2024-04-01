<?php
use yii\bootstrap\Html;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $content string */
/* @var $align string */
/* @var $margin string */
/* @var $breadcrumbs boolean */
/* @var $breadcrumbsInverse boolean */
/* @var $rotatorWords [] */
/* @var $rotatorOptions [] */
/* @var $tabs [] */
/* @var $light boolean */
/* @var $dark boolean */
/* @var $alternate boolean */

$headerClasses = ["page-header-{$margin}", "text-{$align}"];
if ($light)     $headerClasses[] = 'light';
if ($dark)      $headerClasses[] = 'dark';
if ($alternate) $headerClasses[] = 'alternate';
?>
<section class="page-header <?php echo implode(' ', $headerClasses); ?> mt-20">
	<div class="container">
	    <?php if (!empty($rotatorWords)) { ?>
            <h1>
                <?php echo $content; ?>
                <span class="word-rotator" <?php echo json_encode($rotatorOptions); ?>>
                    <span class="items">
                        <?php foreach ($rotatorWords as $word) { ?>
                            <span><?php echo $word; ?></span>
                        <?php } ?>
                    </span>
                </span>
			</h1>
	    <?php } else {
	        echo $content;
        } ?>
        <?php if (!empty($breadcrumbs)) {
            echo Breadcrumbs::widget([
                'tag'     => 'ol',
                'options' => ['class' => implode(' ' , ['breadcrumb', $breadcrumbsInverse ? 'breadcrumb-inverse' : ''])],
                'links'   => $breadcrumbs
            ]);
        } ?>
        <?php if (!empty($tabs)) { ?>
            <ul class="page-header-tabs list-inline">
                <?php foreach ($tabs as $item) { ?>
                    <li <?php if (isset($item['active']) && $item['active']) echo 'class="active"'; ?>>
                        <?php $link = Html::a(
                            $item['label'],
                            isset($item['url']) && (!isset($item['active']) || !$item['active']) ? Yii::$app->urlManager->createUrl($item['url']) : null,
                            isset($item['urlOptions']) ? $item['urlOptions'] : []
                        ); ?>
                        <?php echo Html::tag('li', $link, isset($item['options']) ? $item['options'] : []); ?>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
	</div>
</section>