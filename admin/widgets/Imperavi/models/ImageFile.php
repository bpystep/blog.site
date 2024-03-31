<?php
namespace admin\widgets\Imperavi\models;

use common\components\upload\UploadImageBehavior;

/**
 * Defined methods
 * @method getImageUrl($attribute, $size)
 */
class ImageFile extends File
{
    public $file_b64;

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_merge(parent::rules(), [
            'file'     => [['file'], 'image', 'extensions' => 'jpg, jpeg, gif, png'],
            'file_b64' => [['file_b64'], 'safe']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                'class'       => UploadImageBehavior::class,
                'attribute'   => 'file',
                'scenarios'   => ['default'],
                'path'        => $this->getPath(),
                'url'         => $this->getPath(),
                'thumbs'      => [
                    '270x180' => ['width' => 270, 'height' => 180],
                    '1920'    => ['width' => 1920]
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->type = self::TYPE_IMAGE;
    }

    /**
     * @return ImageFileQuery
     */
    public static function find() {
        return new ImageFileQuery(get_called_class());
    }
}
