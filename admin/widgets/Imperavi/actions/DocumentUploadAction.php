<?php

namespace admin\widgets\Imperavi\actions;

use admin\widgets\Imperavi\models\DocumentFile;

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
