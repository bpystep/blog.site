<?php
namespace admin\widgets\Imperavi\actions;

use Yii;
use admin\widgets\Imperavi\models\ImageFile;

class ImageUploadAction extends UploadAction
{
    /**
     * @return ImageFile
     */
    protected function instantiateModel() {
        return new ImageFile([
            'module' => $this->module
        ]);
    }

    /**
     * @param $model ImageFile
     * @return string
     */
    protected function getUrl($model)
    {
        return $model->getImageUrl('file', '1920');
    }
}
