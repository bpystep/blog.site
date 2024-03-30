<?php

namespace common\components\storage;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class LocalStorage extends Component implements StorageInterface
{
    private string $_basePath = '@common/web/storage';

    private string $_baseUrl  = '@commonUrl/storage';

    public function save(string $filePath, string $name, array $options = []): bool
    {
        $name = ltrim($name, DIRECTORY_SEPARATOR);
        if ($folder = trim(ArrayHelper::getValue($options, 'folder'), DIRECTORY_SEPARATOR)) {
            $name = $folder . DIRECTORY_SEPARATOR . $name;
        }

        if (!ArrayHelper::getValue($options, 'override', true) && $this->fileExists($name)) {
            return false;
        }

        $path = $this->getBasePath() . DIRECTORY_SEPARATOR . $name;
        @mkdir(dirname($path), 0777, true);

        return copy($filePath, $path);
    }

    public function delete(string $name): bool
    {
        return $this->fileExists($name) && @unlink($this->getBasePath() . DIRECTORY_SEPARATOR . $name);
    }

    public function fileExists(string $name): bool
    {
        return file_exists($this->getBasePath() . DIRECTORY_SEPARATOR . $name);
    }

    public function getUrl(string $name): string
    {
        return $this->getBaseUrl() . '/' . $name;
    }

    public function getBasePath(): string
    {
        return Yii::getAlias($this->_basePath);
    }

    public function setBasePath(string $value)
    {
        $this->_basePath = rtrim($value, DIRECTORY_SEPARATOR);
    }

    public function getBaseUrl(): string
    {
        return Yii::getAlias($this->_baseUrl);
    }

    public function setBaseUrl(string $value)
    {
        $this->_baseUrl = rtrim($value, '/');
    }

    public function getLocalPath(string $path): string
    {
        return $this->getBasePath() . DIRECTORY_SEPARATOR . $path;
    }
}
