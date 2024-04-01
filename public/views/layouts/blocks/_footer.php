<?php
/* @var $this       yii\web\View */
/* @var $user       common\modules\user\models\User */
/* @var $controller public\controllers\DefaultController */

$controller = $this->context;
$settings   = $controller->settings;
?>

<footer id="footer">
    <div class="container pt-20">
        <div class="row fs-13">
            <div class="col-md-3 col-sm-3">логотип</div>
            <?php if (!empty($settings->socials)) { ?>
                <div class="col-md-5 col-sm-5">
                    <h4 class="letter-spacing-1"><?php echo Yii::t('*', 'Контакты'); ?></h4>
                    <hr>
                    <address>
                        <ul class="list-unstyled">
                            <?php foreach ($settings->socials as $social) { ?>
                                <li class="footer-sprite <?php echo $social->socialIco; ?>">
                                    <a href="<?php echo $social->urlHtml; ?>" target="_blank"><?php echo Yii::t('*', '{0} - {1}', [$social->socialText, $social->url]); ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </address>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="copyright">
        <div class="container">
            <ul class="float-right m-0 list-inline mobile-block">
                <li><a href="#"><?php echo Yii::t('*', 'Пользовательское соглашение'); ?></a></li>
                <li>&bull;</li>
                <li><a href="#"><?php echo Yii::t('*', 'Конфиденциальность'); ?></a></li>
            </ul>
            &copy; <?php echo 2024 == date('Y') ? 2024 : 2024 . ' - ' . date('Y'); ?>, <?php echo Yii::$app->name; ?>
        </div>
    </div>
</footer>
