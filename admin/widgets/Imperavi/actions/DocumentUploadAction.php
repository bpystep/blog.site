<?php
namespace admin\widgets\imperavi\actions;

use admin\widgets\imperavi\models\DocumentFile;

class DocumentUploadAction extends UploadAction
{
    protected function instantiateModel() {
        return new DocumentFile([
            'module' => $this->module
        ]);
    }

    /**
     * @param $model DocumentFile
     * @return string
     */
    protected function getUrl($model)
    {
        return $model->getUploadUrl('file');
    }
}
