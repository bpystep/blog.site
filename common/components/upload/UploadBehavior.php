<?php

namespace common\components\upload;

use Closure;
use common\components\storage\LocalStorage;
use common\components\storage\StorageInterface;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

class UploadBehavior extends Behavior
{
    const EVENT_AFTER_UPLOAD = 'afterUpload';

    public string $attribute;

    public array $scenarios = [];

    public string $path;

    public string $url;

    public bool $instanceByName = false;

    public bool|Closure $generateNewName = true;

    public bool $unlinkOnSave = true;

    public bool $unlinkOnDelete = true;

    public bool $deleteTempFile = true;

    protected ?UploadedFile $_file = null;

    /* @var LocalStorage */
    protected StorageInterface $storage;

    /* @var ActiveRecord */
    public $owner;

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

    public static function getInstance(ActiveRecord $model, string $attribute): self
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
    public function events(): array
    {
        return [
            Model::EVENT_BEFORE_VALIDATE          => 'beforeValidate',
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            BaseActiveRecord::EVENT_AFTER_INSERT  => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_UPDATE  => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_DELETE  => 'afterDelete'
        ];
    }

    public function beforeValidate(): void
    {
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

    public function beforeSave(): void
    {
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

    public function afterSave(): void
    {
        if ($this->_file instanceof UploadedFile) {
            $path = $this->getUploadPath($this->attribute);
            $this->save($this->_file, $path);
            $this->afterUpload();
        }
    }

    public function afterDelete(): void
    {
        $attribute = $this->attribute;
        if ($this->unlinkOnDelete && $attribute) {
            $this->delete($attribute);
        }
    }

    protected function afterUpload(): void
    {
        $this->owner->trigger(self::EVENT_AFTER_UPLOAD);
    }

    public function getUploadPath(string $attribute, bool $old = false): ?string
    {
        $model = $this->owner;
        $behavior = static::getInstance($model, $attribute);

        $path = $behavior->resolvePath($behavior->path);
        $fileName = ($old === true) ? $model->getOldAttribute($attribute) : $model->{$attribute};

        return $fileName ? Yii::getAlias($path . DIRECTORY_SEPARATOR . $fileName) : null;
    }

    public function getUploadUrl(string $attribute): ?string
    {
        $model = $this->owner;
        $behavior = static::getInstance($model, $attribute);

        $url = $behavior->resolvePath($behavior->url);
        $fileName = $model->getOldAttribute($attribute);

        return $fileName ? $this->storage->getUrl($url . DIRECTORY_SEPARATOR . $fileName) : null;
    }

    protected function resolvePath(string $path): string
    {
        $model = $this->owner;

        return preg_replace_callback('/{([^}]+)}/', function($matches) use ($model) {
            $name = $matches[1];
            $attribute = ArrayHelper::getValue($model, $name);

            if (is_string($attribute) || is_numeric($attribute)) {
                return $attribute;
            }

            return $matches[0];
        }, $path);
    }

    protected function save(UploadedFile $file, string $path): bool
    {
        return $this->storage->save($file->tempName, $path);
    }

    protected function delete(string $attribute, bool $old = false): void
    {
        $path = $this->getUploadPath($attribute, $old);
        if (!empty($path) && $this->storage->fileExists($path)) {
            $this->storage->delete($path);
        }
    }

    protected function getFileName(UploadedFile $file): string
    {
        if ($this->generateNewName) {
            return $this->generateNewName instanceof Closure ?
                call_user_func($this->generateNewName, $file->name, $file->extension) :
                $this->generateFileName($file->extension);
        }

        return $this->sanitize($file->name);
    }

    public static function sanitize(string $filename): string|array
    {
        return str_replace([' ', '"', '\'', '&', '/', '\\', '?', '#'], '-', $filename);
    }

    protected function generateFileName(string $extension): string
    {
        return uniqid() . '.' . $extension;
    }
}
