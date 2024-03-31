<?php
namespace admin\widgets\Imperavi\actions;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\web\UploadedFile;
use admin\widgets\Imperavi\models\File;
use yii\base\InvalidConfigException;

class UploadAction extends Action
{
    /* @var $module string */
    public $module;

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if (empty($this->module)) {
                throw new InvalidConfigException('The "module" property must be required.');
            }

            if ($file = UploadedFile::getInstanceByName('file')) {
                $model = $this->instantiateModel();
                $model->file = $file;
                if ($model->save()) {
                    return [
                        'success' => true,
                        'url'     => $this->getUrl($model)
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Something went wrong',
            ];
        }
    }

    protected function instantiateModel()
    {
        return new File([
            'module' => $this->module
        ]);
    }

    /**
     * @param File $model
     * @return string
     */
    protected function getUrl($model)
    {
        return $model->getUploadUrl('file');
    }
}
