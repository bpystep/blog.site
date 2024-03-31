<?php
namespace admin\widgets\Imperavi\models;

use \yii\db\ActiveQuery;

class DocumentFileQuery extends ActiveQuery
{
    public function init() {
        $this->where(['type' => File::TYPE_DOCUMENT]);
        parent::init();
    }
}
