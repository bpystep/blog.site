<?php
/* @var $this      yii\web\View */
/* @var $exception Exception */

switch ($exception->statusCode) {
    case 404: $this->title = Yii::t('error', 'Страница не найдена'); break;
    case 403: $this->title = Yii::t('error', 'Ошибка доступа'); break;
    case 500: $this->title = Yii::t('error', 'Внутренняя ошибка сервера'); break;
    default : $this->title = Yii::t('error', 'Ошибка');
}
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-4">
        <div class="card">
            <div class="card-body p-4">
                <div class="text-center w-75 m-auto">
                    <div class="auth-logo">
                        <a href="<?php echo Yii::$app->urlManager->createUrl(['/main/index']); ?>" class="logo logo-dark text-center">
                            <span class="logo-lg">
                                <i class="fas fa-blog" style="color: #ffffff; font-size: 32px;"></i>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <h1 class="text-error"><?php echo $exception->statusCode; ?></h1>
                    <h3 class="mt-3 mb-2"><?php echo $exception->getMessage(); ?></h3>
                    <p class="text-muted mb-3"><?php echo Yii::t('error', 'Мы знаем об ошибке и работаем над её устранением'); ?></p>
                    <a href="<?php echo Yii::$app->urlManager->createUrl(['/main/index']); ?>" class="btn btn-success waves-effect waves-light"><?php echo Yii::t('common', 'Вернуться на главную'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
