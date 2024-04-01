<?php

namespace common\components\storage;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * @inheritdoc
 */
class LocalStorage extends Component implements StorageInterface
{
    /* @var $_basePath string */
    private $_basePath = '@common/web/storage';
    /* @var $_baseUrl string */
    private $_baseUrl = '@commonUrl/storage';

    /**
     * @param $filePath string
     * @param $name string
     * @param $options []
     * @return boolean
     */
    public function save($filePath, $name, $options = [])
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

    /**
     * @param $name string
     * @return boolean
     */
    public function delete($name)
    {
        return $this->fileExists($name) ? @unlink($this->getBasePath() . DIRECTORY_SEPARATOR . $name) : false;
    }

    /**
     * @param $name string
     * @return boolean
     */
    public function fileExists($name)
    {
        return file_exists($this->getBasePath() . DIRECTORY_SEPARATOR . $name);
    }

    /**
     * @param $name string
     * @return string
     */
    public function getUrl($name)
    {
        return $this->getBaseUrl() . '/' . $name;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return Yii::getAlias($this->_basePath);
    }

    /**
     * @param $value string
     */
    public function setBasePath($value)
    {
        $this->_basePath = rtrim($value, DIRECTORY_SEPARATOR);
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return Yii::getAlias($this->_baseUrl);
    }

    /**
     * @param $value string
     */
    public function setBaseUrl($value)
    {
        $this->_baseUrl = rtrim($value, '/');
    }

    public function getLocalPath($path)
    {
        return $this->getBasePath() . DIRECTORY_SEPARATOR . $path;
    }
}
