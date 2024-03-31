<?php

namespace common\components\upload;

use Imagine\Image\ManipulatorInterface;
use Yii;
use Closure;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\imagine\Image;

/**
 * @inheritdoc
 */
class UploadImageBehavior extends UploadBehavior
{
    /* @var $placeholder string|callable */
    public $placeholder;
    /* @var $thumbs [] */
    public $thumbs = [
        'thumb' => [
            'width'   => 200,
            'height'  => 200,
            'quality' => 100
        ]
    ];
    /* @var $_imagePath string */
    protected $_imagePath;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        foreach ($this->thumbs as $thumb) {
            $width = ArrayHelper::getValue($thumb, 'width');
            $height = ArrayHelper::getValue($thumb, 'height');
            if ($height < 1 && $width < 1) {
                throw new InvalidConfigException(sprintf('Length of either side of thumb cannot be 0 or negative, current size ' . 'is %sx%s', $width, $height));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeSave()
    {
        /* @var ActiveRecord $model */
        $model = $this->owner;
        if (!empty($b64 = $model->{$this->attribute . '_b64'})) {
            $this->attachB64Image($this->attribute, $b64);
        }
        if (!empty($this->_imagePath)) {
            $info = pathinfo($this->_imagePath);
            if ($this->generateNewName instanceof Closure) {
                $imageName = call_user_func($this->generateNewName, $info['filename'], $info['extension']);
            } else {
                $imageName = $this->generateFileName($info['filename'], $info['extension']);
            }
            $model->setAttribute($this->attribute, $imageName);
            if ($this->unlinkOnSave === true) {
                $this->delete($this->attribute, true);
            }
        } else {
            parent::beforeSave();
        }
    }

    /**
     * @inheritdoc
     */
    public function afterSave()
    {
        if (!empty($this->_imagePath)) {
            $path = $this->getUploadPath($this->attribute);
            $this->storage->save($this->_imagePath, $path);
            $this->createThumbs($this->_imagePath);
        } else {
            parent::afterSave();
        }
    }

    /**
     * @inheritdoc
     */
    protected function afterUpload()
    {
        $this->createThumbs();
        parent::afterUpload();
    }

    /**
     * @inheritdoc
     */
    protected function delete($attribute, $old = false)
    {
        parent::delete($attribute, $old);
        foreach (array_keys($this->thumbs) as $thumbName) {
            $path = $this->getUploadPath($attribute, $thumbName, $old);
            if (!empty($path) && $this->storage->fileExists($path)) {
                $this->storage->delete($path);
            }
        }
    }

    /**
     * @param $attribute string
     * @param $imagePath string
     */
    public function attachImage($attribute, $imagePath)
    {
        /* @var $model ActiveRecord */
        $model = $this->owner;
        $behavior = static::getInstance($model, $attribute);
        $behavior->_imagePath = Yii::getAlias($imagePath);
    }

    /**
     * @param $attribute string
     * @param $b64Image string
     */
    protected function attachB64Image($attribute, $b64Image)
    {
        preg_match('/^data:image\/(?<extension>\w+);base64,/i', $b64Image, $matches);
        $extension = !empty($matches['extension']) ? $matches['extension'] : 'jpg';
        $extension = $this->prepareExtension($extension);
        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $b64Image));
        $imagePath = Yii::$app->getRuntimePath() . '/cropper/';
        if (!is_dir($imagePath)) {
            FileHelper::createDirectory($imagePath);
        }
        $imagePath .= implode('.', [uniqid(), $extension]);
        file_put_contents($imagePath, $image);
        /* @var $model ActiveRecord */
        $model = $this->owner;
        $behavior = static::getInstance($model, $attribute);
        $behavior->_imagePath = Yii::getAlias($imagePath);
    }

    /**
     * @param $extension string
     * @return string
     */
    protected function prepareExtension($extension)
    {
        $map = [
            'jpeg' => 'jpg'
        ];

        return isset($map[$extension]) ? $map[$extension] : $extension;
    }

    /**
     * @param $path string
     */
    protected function createThumbs($path = null)
    {
        if (empty($path) && !empty($this->_file)) {
            $path = $this->_file->tempName;
        }
        foreach ($this->thumbs as $thumbName => $config) {
            $thumbPath = $this->getUploadPath($this->attribute, $thumbName);
            if ($thumbPath !== null) {
                if (!$this->storage->fileExists($thumbPath)) {
                    $this->generateImageThumb($config, $path, $thumbPath);
                }
            }
        }
        @unlink($path);
    }

    /**
     * @param $attribute string
     * @param $thumbName string
     * @param $old boolean
     * @return string
     */
    public function getUploadPath($attribute, $thumbName = 'thumb', $old = false)
    {
        /* @var ActiveRecord $model */
        $model = $this->owner;
        $path = $this->resolvePath($this->path);
        $attribute = ($old === true) ? $model->getOldAttribute($attribute) : $model->{$attribute};
        if (!empty($attribute)) {
            $filename = $this->getThumbFileName($attribute, $thumbName);

            return $filename ? Yii::getAlias($path . DIRECTORY_SEPARATOR . $filename) : null;
        }

        return null;
    }

    /**
     * @param $attribute string
     * @param $thumbName string
     * @return string|null
     */
    public function getImageUrl($attribute, $thumbName = 'thumb')
    {
        /* @var ActiveRecord $model */
        $model = $this->owner;
        $path = $this->getUploadPath($attribute, true);
        $behavior = static::getInstance($model, $attribute);
        if (!empty($path)) {
            $url = $behavior->resolvePath($behavior->url);
            $fileName = $model->getOldAttribute($attribute);
            $thumbName = $this->getThumbFileName($fileName, $thumbName);

            return $this->storage->getUrl($url . '/' . $thumbName);
        } else if ($behavior->getPlaceholder()) {
            return $behavior->getPlaceholderUrl($thumbName);
        } else {
            return null;
        }
    }

    /**
     * @return string|callable
     */
    public function getPlaceholder()
    {
        if ($this->placeholder instanceof Closure) {
            return call_user_func($this->placeholder, $this->owner);
        }
        if (is_array($this->placeholder)) {
            return $this->placeholder[rand(0, (count($this->placeholder) - 1))];
        }

        return $this->placeholder;
    }

    /**
     * @param $thumbName string
     * @return string
     */
    protected function getPlaceholderUrl($thumbName)
    {
        [$path, $url] = Yii::$app->assetManager->publish($this->getPlaceholder());
        $filename = basename($path);
        $thumb = $this->getThumbFileName($filename, $thumbName);
        $thumbPath = dirname($path) . DIRECTORY_SEPARATOR . $thumb;
        $thumbUrl = dirname($url) . '/' . $thumb;
        if (!is_file($thumbPath)) {
            $this->generateImageThumb($this->thumbs[$thumbName], $path, $thumbPath, true);
        }

        return $thumbUrl;
    }

    /**
     * @param $filename string
     * @param $thumbName string
     * @return string
     */
    protected function getThumbFileName($filename, $thumbName = 'thumb')
    {
        $info = pathinfo($filename);

        return "{$info['filename']}_{$thumbName}.{$info['extension']}";
    }

    /**
     * @param $config []
     * @param $path string
     * @param $thumbPath string
     * @param $local boolean
     */
    protected function generateImageThumb($config, $path, $thumbPath, $local = false)
    {
        $width = ArrayHelper::getValue($config, 'width');
        $height = ArrayHelper::getValue($config, 'height');
        $quality = ArrayHelper::getValue($config, 'quality', 100);
        $mode = ArrayHelper::getValue($config, 'mode', ManipulatorInterface::THUMBNAIL_OUTBOUND);
        $info = pathinfo($path);
        if (isset($info['extension']) && $info['extension'] === 'svg') {
            copy($path, $thumbPath);

            return;
        }
        if (!$width || !$height) {
            $image = Image::getImagine()->open($path);
            $ratio = $image->getSize()->getWidth() / $image->getSize()->getHeight();
            if ($width) {
                $height = ceil($width / $ratio);
            } else {
                $width = ceil($height * $ratio);
            }
        }
        //Fix error "PHP GD Allowed memory size exhausted".
        ini_set('memory_limit', '512M');
        if ($local) {
            Image::thumbnail($path, $width, $height, $mode)->save($thumbPath, ['quality' => $quality]);
        } else {
            $tempThumbPath = Yii::$app->getRuntimePath() . '/' . uniqid() . '.' . pathinfo($thumbPath, \PATHINFO_EXTENSION);
            Image::thumbnail($path, $width, $height, $mode)->save($tempThumbPath, ['quality' => $quality]);
            $this->storage->save($tempThumbPath, $thumbPath);
            @unlink($tempThumbPath);
        }
    }
}
