<?php
/* @var $this yii\web\View */
/* @var $user common\modules\user\models\User */
?>

<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <?php echo implode(' - ', array_filter([2024, date('Y') != 2024 ? date('Y') : null])) ?> &#0169; <a href="<?php echo Yii::getAlias('@adminUrl'); ?>"><?php echo Yii::$app->name; ?></a>
            </div>
        </div>
    </div>
</footer>
