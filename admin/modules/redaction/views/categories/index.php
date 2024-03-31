<?php
use admin\helpers\HtmlHelper;
use common\modules\redaction\models\Category;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this         yii\web\View */
/* @var $searchModel  common\modules\redaction\searches\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('redaction', 'Категории');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="btn-toolbar justify-content-end">
    <div class="btn-group">
        <?php echo HtmlHelper::a(Yii::t('app', 'Сбросить фильтр'), ['search'], ['class' => 'btn btn-danger'], 'fas fa-times'); ?>
        <?php echo HtmlHelper::a(Yii::t('redaction', 'Создать категорию'), ['create'], ['class' => 'btn btn-success'], 'fas fa-plus', true); ?>
    </div>
</div>

<div class="card">
    <?php Pjax::begin(); ?>
    <div class="card-body">
        <?php echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'layout'       => "{items}",
            'options'      => ['class' => 'scroll-table'],
            'tableOptions' => ['class' => 'table table-striped table-bordered mb-0'],
            'columns'      => [
                [
                    'attribute'      => 'category_id',
                    'label'          => '#',
                    //'class'          => 'yii\grid\SerialColumn',
                    'headerOptions'  => ['class' => 'mobile-hide text-center', 'style' => 'width: 82px;'],
                    'filterOptions'  => ['class' => 'mobile-hide'],
                    'contentOptions' => ['class' => 'mobile-hide text-center']
                ],
                [
                    'attribute' => 'title'
                ],
                [
                    'class'          => 'yii\grid\ActionColumn',
                    'template'       => '<div class="d-grid"><div class="btn-group">{update}{delete}</div></div>',
                    'buttons'        => [
                        'update' => function($url, Category $model) {
                            return HtmlHelper::a('<i class="fas fa-pencil-alt"></i>', $url, [
                                'class'     => 'btn btn-xs btn-block btn-primary add-tooltip',
                                'title'     => Yii::t('app', 'Изменить'),
                                'data-pjax' => 0
                            ]);
                        },
                        'delete' => function($url, Category $model) {
                            return HtmlHelper::a('<i class="fas fa-trash"></i>', null, [
                                'class'        => 'btn btn-xs btn-danger add-tooltip js-sweet-alert',
                                'title'        => Yii::t('app', 'Удалить'),
                                'data'      => [
                                    'bb-url'     => $url,
                                    'bb-icon'    => 'question',
                                    'bb-title'   => Yii::t('app', 'Подтверждение'),
                                    'bb-message' => Yii::t('redaction', 'Вы уверены, что хотите удалить категорию?'),
                                    'bb-confirm' => Yii::t('app', 'Да'),
                                    'bb-method'  => 'post',
                                    'pjax'       => 0
                                ]
                            ]);
                        }
                    ],
                    'headerOptions'  => ['class' => 'text-center', 'style' => 'width: 92px;'],
                    'contentOptions' => ['class' => 'text-center action-cell']
                ]
            ]
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
    <?php if ($dataProvider->count < $dataProvider->totalCount) { ?>
        <div class="card-footer d-flex justify-content-end">
            <?php echo admin\components\LinkPager::widget([
                'pagination' => $dataProvider->getPagination(),
                'options'    => ['id' => 'categories-pagination']
            ]); ?>
        </div>
    <?php } ?>
</div>
