<?php

namespace common\components\upload;

use Closure;
use common\components\storage\LocalStorage;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

/**
 * @inheritdoc
 */
class UploadBehavior extends Behavior
{
    const EVENT_AFTER_UPLOAD = 'afterUpload';
    /* @var $attribute string */
    public $attribute;
    /* @var $scenarios [] */
    public $scenarios = [];
    /* @var $path string */
    public $path;
    /* @var $url string */
    public $url;
    /* @var $instanceByName boolean */
    public $instanceByName = false;
    /* @var $generateNewName boolean|callable */
    public $generateNewName = true;
    /* @var $unlinkOnSave boolean */
    public $unlinkOnSave = true;
    /* @var $unlinkOnDelete boolean */
    public $unlinkOnDelete = true;
    /* @var $deleteTempFile boolean $deleteTempFile */
    public $deleteTempFile = true;
    /* @var $_file UploadedFile */
    protected $_file;
    /* @var $storage LocalStorage */
    protected $storage;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $required = [
            'attribute' => 'The "attribute" property must be set.',
            'path'      => 'The "path" property must be set.',
            'url'       => 'The "url" property must be set.'
        ];
        foreach ($required as $key => $message) {
            if ($this->{$key} === null) {
                throw new InvalidConfigException($message);
            }
        }
        $this->storage = Yii::$app->storage;
    }

    /**
     * @param $model ActiveRecord
     * @param $attribute string
     * @return static
     */
    public static function getInstance(ActiveRecord $model, $attribute)
    {
        foreach ($model->behaviors as $behavior) {
            if ($behavior instanceof self && $behavior->attribute == $attribute) {
                return $behavior;
            }
        }
        throw new InvalidCallException('Missing behavior for attribute ' . VarDumper::dumpAsString($attribute));
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            BaseActiveRecord::EVENT_BEFORE_INSERT   => 'beforeSave',
            BaseActiveRecord::EVENT_BEFORE_UPDATE   => 'beforeSave',
            BaseActiveRecord::EVENT_AFTER_INSERT    => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_UPDATE    => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_DELETE    => 'afterDelete',
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        /* @var ActiveRecord $model */
        $model = $this->owner;
        if (in_array($model->scenario, $this->scenarios)) {
            if (($file = $model->getAttribute($this->attribute)) instanceof UploadedFile) {
                $this->_file = $file;
            } else {
                if ($this->instanceByName === true) {
                    $this->_file = UploadedFile::getInstanceByName($this->attribute);
                } else {
                    $this->_file = UploadedFile::getInstance($model, $this->attribute);
                }
            }
            if ($this->_file instanceof UploadedFile) {
                $this->_file->name = $this->getFileName($this->_file);
                $model->setAttribute($this->attribute, $this->_file);
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
        if (in_array($model->scenario, $this->scenarios)) {
            if ($this->_file instanceof UploadedFile) {
                if (!$model->getIsNewRecord() && $model->isAttributeChanged($this->attribute)) {
                    if ($this->unlinkOnSave === true) {
                        $this->delete($this->attribute, true);
                    }
                }
                $model->setAttribute($this->attribute, $this->_file->name);
            } else {
                unset($model->{$this->attribute});
            }
        } else if (!$model->isNewRecord && $model->isAttributeChanged($this->attribute)) {
            if ($this->unlinkOnSave === true) {
                $this->delete($this->attribute, true);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function afterSave()
    {
        if ($this->_file instanceof UploadedFile) {
            $path = $this->getUploadPath($this->attribute);
            $this->save($this->_file, $path);
            $this->afterUpload();
        }
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        $attribute = $this->attribute;
        if ($this->unlinkOnDelete && $attribute) {
            $this->delete($attribute);
        }
    }

    /**
     * @inheritdoc
     */
    protected function afterUpload()
    {
        $this->owner->trigger(self::EVENT_AFTER_UPLOAD);
    }

    /**
     * @param $attribute string
     * @param $old boolean
     * @return string|null
     */
    public function getUploadPath($attribute, $old = false)
    {
        /* @var ActiveRecord $model */
        $model = $this->owner;
        $behavior = static::getInstance($model, $attribute);
        $path = $behavior->resolvePath($behavior->path);
        $fileName = ($old === true) ? $model->getOldAttribute($attribute) : $model->{$attribute};

        return $fileName ? Yii::getAlias($path . DIRECTORY_SEPARATOR . $fileName) : null;
    }

    /**
     * Returns file url for the attribute.
     * @param string $attribute
     * @return string|null
     */
    public function getUploadUrl($attribute)
    {
        /* @var ActiveRecord $model */
        $model = $this->owner;
        $behavior = static::getInstance($model, $attribute);
        $url = $behavior->resolvePath($behavior->url);
        $fileName = $model->getOldAttribute($attribute);

        return $fileName ? $this->storage->getUrl($url . DIRECTORY_SEPARATOR . $fileName) : null;
    }

    /**
     * @param $path string
     * @return mixed
     */
    protected function resolvePath($path)
    {
        /* @var ActiveRecord $model */
        $model = $this->owner;

        return preg_replace_callback('/{([^}]+)}/', function($matches) use ($model) {
            $name = $matches[1];
            $attribute = ArrayHelper::getValue($model, $name);
            if (is_string($attribute) || is_numeric($attribute)) {
                return $attribute;
            } else {
                return $matches[0];
            }
        }, $path);
    }

    /**
     * @param $file UploadedFile
     * @param $path string
     * @return boolean
     */
    protected function save($file, $path)
    {
        return $this->storage->save($file->tempName, $path);
    }

    /**
     * @param $attribute string
     * @param $old boolean
     */
    protected function delete($attribute, $old = false)
    {
        $path = $this->getUploadPath($attribute, $old);
        if (!empty($path) && $this->storage->fileExists($path)) {
            $this->storage->delete($path);
        }
    }

    /**
     * @param $file UploadedFile
     * @return string
     */
    protected function getFileName($file)
    {
        if ($this->generateNewName) {
            return $this->generateNewName instanceof Closure ? call_user_func($this->generateNewName, $file->name, $file->extension) : $this->generateFileName($file->name, $file->extension);
        } else {
            return $this->sanitize($file->name);
        }
    }

    /**
     * @param $filename string
     * @return boolean
     */
    public static function sanitize($filename)
    {
        return str_replace([' ', '"', '\'', '&', '/', '\\', '?', '#'], '-', $filename);
    }

    /**
     * @param $filename string
     * @param $extension string
     * @return string
     */
    protected function generateFileName($filename, $extension)
    {
        return uniqid() . '.' . $extension;
    }
}
