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

<?php echo $exception->statusCode; ?>
<br>
<?php echo $exception->getMessage(); ?>
<br>
<?php echo Yii::t('error', 'Мы знаем об ошибке и работаем над её устранением'); ?>
