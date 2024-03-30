<?php

namespace common\traits;

use yii\base\ExitException;
use yii\widgets\ActiveForm;
use Yii;
use yii\base\Model;
use yii\web\Response;

trait AjaxValidationTrait
{
    /**
     * @throws ExitException
     */
    protected function performAjaxValidation(Model $model): void
    {
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            Yii::$app->response->data = ActiveForm::validate($model);

            Yii::$app->end();
        }
    }

    /**
     * @param Model[] $models
     * @throws ExitException
     */
    protected function performAjaxMultiValidation(array $models = []): void
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            foreach ($models as $model) {
                if ($model->load(Yii::$app->request->post())) {
                    $data = Yii::$app->response->data ?: [];
                    Yii::$app->response->data = array_merge(ActiveForm::validate($model), $data);
                }
            }

            Yii::$app->end();
        }
    }

    /**
     * @param Model[][] $models
     * @throws ExitException
     */
    protected function performAjaxMultiSameValidation(array $models = []): void
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            foreach ($models as $group) {
                if (Model::loadMultiple($group, Yii::$app->request->post())) {
                    $data = Yii::$app->response->data ?: [];
                    Yii::$app->response->data = array_merge(ActiveForm::validateMultiple($group), $data);
                }
            }

            Yii::$app->end();
        }
    }
}
