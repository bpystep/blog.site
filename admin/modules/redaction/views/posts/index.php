<?php
use admin\components\DatePicker;
use admin\helpers\HtmlHelper;
use common\modules\redaction\models\Post;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this         yii\web\View */
/* @var $searchModel  common\modules\redaction\searches\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $categories   common\modules\redaction\models\Category[] */

$this->title = Yii::t('redaction', 'Новости');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="btn-toolbar justify-content-end">
    <div class="btn-group">
        <?php echo HtmlHelper::a(Yii::t('app', 'Сбросить фильтр'), ['index'], ['class' => 'btn btn-danger'], 'fas fa-times'); ?>
        <?php echo HtmlHelper::a(Yii::t('redaction', 'Создать новость'), ['create'], ['class' => 'btn btn-success'], 'fas fa-plus', true); ?>
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
                    'attribute'      => 'post_id',
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
                    'attribute'      => 'published_dt',
                    'value'          => function(Post $model) {
                        return $model->published_dt ? Yii::$app->formatter->asDate($model->publishedDt, 'dd.MM.yyyy, HH:mm') : null;
                    },
                    'filter'         => DatePicker::widget([
                        'model'     => $searchModel,
                        'attribute' => 'published_dt',
                        'options'   => [
                            'value' => $searchModel->published_dt ? Yii::$app->formatter->asDate($searchModel->published_dt, 'dd.MM.yyyy') : null,
                        ]
                    ]),
                    'headerOptions'  => ['class' => 'text-center', 'style' => 'width: 178px;'],
                    'contentOptions' => ['class' => 'text-center']
                ],
                [
                    'attribute'          => 'is_public',
                    'label'              => Yii::t('redaction', 'Статус'),
                    'value'              => function(Post $model) {
                        return $model->getPublishStatusLabel(true);
                    },
                    'format'             => 'raw',
                    'filter'             => Post::getPublishStatusLabels(),
                    'filterInputOptions' => ['prompt' => Yii::t('redaction', 'Все статусы'), 'style' => 'width: 100%;'],
                    'headerOptions'      => ['class' => 'text-center', 'style' => 'width: 200px;'],
                    'contentOptions'     => ['class' => 'text-center']
                ],
                [
                    'class'          => 'yii\grid\ActionColumn',
                    'template'       => '<div class="d-grid"><div class="btn-group">{update}{delete}</div></div>',
                    'buttons'        => [
                        'update' => function($url, Post $model) {
                            return HtmlHelper::a('<i class="fas fa-pencil-alt"></i>', $url, [
                                'class'     => 'btn btn-xs btn-block btn-primary add-tooltip',
                                'title'     => Yii::t('app', 'Изменить'),
                                'data-pjax' => 0
                            ]);
                        },
                        'delete' => function($url, Post $model) {
                            return HtmlHelper::a('<i class="fas fa-trash"></i>', null, [
                                'class'        => 'btn btn-xs btn-danger add-tooltip js-sweet-alert',
                                'title'        => Yii::t('app', 'Удалить'),
                                'data'      => [
                                    'bb-url'     => $url,
                                    'bb-icon'    => 'question',
                                    'bb-title'   => Yii::t('app', 'Подтверждение'),
                                    'bb-message' => Yii::t('redaction', 'Вы уверены, что хотите удалить новость?'),
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
            <?php echo \admin\components\LinkPager::widget([
                'pagination' => $dataProvider->getPagination(),
                'options'    => ['id' => 'posts-pagination']
            ]); ?>
        </div>
    <?php } ?>
</div>
