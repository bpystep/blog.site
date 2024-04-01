<?php
use yii\widgets\Breadcrumbs;

/* @var $this       yii\web\View */
/* @var $content    string */
/* @var $controller admin\controllers\DefaultController */

$controller = $this->context;
$user = $controller->user;
?>
<?php $this->beginContent('@admin/views/layouts/default.php'); ?>
    <div id="wrapper">
        <?php echo $this->render('blocks/_header', ['user' => $user]); ?>
        <?php echo $this->render('blocks/_nav', ['user' => $user]); ?>
        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <?php if (empty($this->blocks['navigation'])) { ?>
                                    <h4 class="page-title"><?php echo $this->title; ?></h4>
                                <?php } ?>
                                <?php if (!empty($this->params['breadcrumbs'])) { ?>
                                    <div class="page-title-right">
                                        <?php echo Breadcrumbs::widget([
                                            'homeLink'           => ['label' => Yii::t('admin', 'Главная'), '/main/index'],
                                            'links'              => $this->params['breadcrumbs'] ?? [],
                                            'options'            => ['class' => 'breadcrumb m-0'],
                                            'itemTemplate'       => '<li class="breadcrumb-item">{link}</li>',
                                            'activeItemTemplate' => '<li class="breadcrumb-item active">{link}</li>'
                                        ]); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($this->blocks['navigation'])) { ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box page-title-box-navigation">
                                    <?php echo $this->blocks['navigation']; ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (!empty($this->blocks['sub-navigation'])) { ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box page-title-box-navigation">
                                    <?php echo $this->blocks['sub-navigation']; ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (!empty($flashes = Yii::$app->session->getAllFlashes())) { ?>
                        <div class="row">
                            <div class="col-12">
                                <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message) { ?>
                                    <?php if (in_array($type, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'])) { ?>
                                        <div class="alert alert-<?php echo $type; ?> bg-<?php echo $type; ?> alert-dismissible <?php echo $type == 'light' ? 'text-dark' : 'text-white'; ?> border-0 fade show mb-3" role="alert">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            <?php echo $message; ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php echo $content; ?>
                </div>
            </div>
            <?php echo $this->render('blocks/_footer', ['user' => $user]); ?>
        </div>
    </div>

    <?php if (isset($this->params['modals'])) {
        foreach ($this->params['modals'] as $modals) {
            echo $modals;
        }
    } ?>
<?php $this->endContent(); ?>
