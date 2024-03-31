<?php
namespace admin\widgets\imperavi\models;

/**
 * Defined methods
 * @method getUploadUrl($attribute)
 */
class DocumentFile extends File
{
    /**
     * @inheritdoc
     */
    public function rules() {
        return array_merge(parent::rules(), [
            'file' => [['file'], 'file', 'extensions' => 'pdf, doc, docx, xls, xlsx']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->type = self::TYPE_DOCUMENT;
    }

    /**
     * @return DocumentFileQuery
     */
    public static function find() {
        return new DocumentFileQuery(get_called_class());
    }
}
