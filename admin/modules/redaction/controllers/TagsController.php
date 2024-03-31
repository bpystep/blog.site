<?php

namespace admin\modules\redaction\controllers;

use common\modules\redaction\models\Tag;
use common\modules\redaction\searches\TagSearch;
use common\traits\AjaxValidationTrait;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class TagsController extends DefaultController
{
    use AjaxValidationTrait;

    public ?Tag $tag = null;

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST']
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     * @throws NotFoundHttpException
     */
    public function beforeAction($action): bool
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        if ($tagId = Yii::$app->request->get('id')) {
            $this->tag = Tag::findOne($tagId);
            if (!$this->tag) {
                throw new NotFoundHttpException();
            }
        }

        return true;
    }

    public function actionIndex(): string
    {
        $searchModel = new TagSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * @throws yii\base\ExitException
     */
    public function actionCreate(): string|Response
    {
        $this->tag = new Tag();
        $this->performAjaxValidation($this->tag);
        if ($this->tag->load(Yii::$app->request->post())) {
            if ($this->tag->save()) {
                Yii::$app->session->setFlash('success', Yii::t('redaction', 'Тег добавлен!'));
                return $this->redirect(['update', 'id' => $this->tag->tag_id]);
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('redaction', 'Ошибка при добавлении тега!'));
            }
        }

        return $this->render('create');
    }

    /**
     * @throws yii\base\ExitException
     */
    public function actionUpdate(int $id): string|Response
    {
        $this->performAjaxValidation($this->tag);
        if ($this->tag->load(Yii::$app->request->post())) {
            if ($this->tag->save()) {
                Yii::$app->session->setFlash('success', Yii::t('redaction', 'Тег обновлен!'));
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('redaction', 'Ошибка при обновлении тега!'));
            }
        }

        return $this->render('update');
    }

    /**
     * @throws \Throwable
     * @throws yii\db\StaleObjectException
     */
    public function actionDelete(int $id): Response
    {
        if ($this->tag->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('redaction', 'Тег удален!'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('redaction', 'Ошибка при удалении тега!'));
        }

        return $this->redirect(['index']);
    }

    /**
     * @inheritdoc
     */
    public function render($view, $params = []): string
    {
        return parent::render($view, array_merge([
            'tag' => $this->tag
        ], $params));
    }
}
