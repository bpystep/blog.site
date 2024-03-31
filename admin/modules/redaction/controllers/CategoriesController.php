<?php

namespace admin\modules\redaction\controllers;

use common\modules\redaction\models\Category;
use common\modules\redaction\searches\CategorySearch;
use common\traits\AjaxValidationTrait;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CategoriesController extends DefaultController
{
    use AjaxValidationTrait;

    public ?Category $category = null;

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
        if ($categoryId = Yii::$app->request->get('id')) {
            $this->category = Category::findOne($categoryId);
            if (!$this->category) {
                throw new NotFoundHttpException();
            }
        }

        return true;
    }

    public function actionIndex(): string
    {
        $searchModel = new CategorySearch();
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
        $this->category = new Category();
        $this->performAjaxValidation($this->category);
        if ($this->category->load(Yii::$app->request->post())) {
            if ($this->category->save()) {
                Yii::$app->session->setFlash('success', Yii::t('redaction', 'Категория добавлена!'));
                return $this->redirect(['update', 'id' => $this->category->category_id]);
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('redaction', 'Ошибка при добавлении категории!'));
            }
        }

        return $this->render('create');
    }

    /**
     * @throws yii\base\ExitException
     */
    public function actionUpdate(int $id): string|Response
    {
        $this->performAjaxValidation($this->category);
        if ($this->category->load(Yii::$app->request->post())) {
            if ($this->category->save()) {
                Yii::$app->session->setFlash('success', Yii::t('redaction', 'Категория обновлена!'));
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('redaction', 'Ошибка при обновлении категории!'));
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
        if ($this->category->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('redaction', 'Категория удалена!'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('redaction', 'Ошибка при удалении категории!'));
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @inheritdoc
     */
    public function render($view, $params = []): string
    {
        return parent::render($view, array_merge([
            'category' => $this->category
        ], $params));
    }
}
