<?php

namespace admin\modules\redaction\controllers;

use admin\widgets\Imperavi\actions\DocumentUploadAction;
use admin\widgets\Imperavi\actions\GetImagesAction;
use admin\widgets\Imperavi\actions\ImageUploadAction;
use common\modules\redaction\models\Category;
use common\modules\redaction\models\Post;
use common\modules\redaction\models\Tag;
use common\modules\redaction\searches\PostSearch;
use common\traits\AjaxValidationTrait;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class PostsController extends DefaultController
{
    use AjaxValidationTrait;

    public ?Post $post = null;

    public function actions(): array
    {
        return [
            'image-upload' => ['class' => ImageUploadAction::class,    'module' => 'post'],
            'file-upload'  => ['class' => DocumentUploadAction::class, 'module' => 'post'],
            'get-images'   => ['class' => GetImagesAction::class,      'module' => 'post']
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

        if ($postId = Yii::$app->request->get('id')) {
            $this->post = Post::findOne($postId);
            if (!$this->post) {
                throw new NotFoundHttpException();
            }
        }

        return true;
    }

    public function actionIndex(): string
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $categories = Category::find()->sort()->all();

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'categories'   => $categories
        ]);
    }

    public function actionCreate(): Response
    {
        $this->post = new Post(['is_temp' => true]);

        if ($this->post->save(false)) {
            return $this->redirect(['update', 'id' => $this->post->post_id]);
        }

        Yii::$app->session->addFlash('danger', Yii::t('redaction', 'Ошибка при создании новости!'));
        return $this->redirect('index');
    }

    public function actionUpdate(int $id): string|Response
    {
        $this->performAjaxValidation($this->post);
        if ($this->post->load(Yii::$app->request->post())) {
            if ($this->post->save()) {
                Yii::$app->session->setFlash('success', Yii::t('redaction', 'Новость сохранена!'));
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('redaction', 'Ошибка при сохранении новости!'));
            }
        }

        $categories = Category::find()->sort()->all();
        $tags = Tag::find()->sort()->all();

        return $this->render('update', [
            'categories' => $categories,
            'tags'       => $tags
        ]);
    }

    public function actionDelete(int $id): Response
    {
        if ($this->post->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('redaction', 'Новость удалена!'));
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('redaction', 'Ошибка при удалении новости!'));
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function render($view, $params = []): string
    {
        return parent::render($view, array_merge([
            'post' => $this->post
        ], $params));
    }
}
