<?php

namespace common\components\storage;

/**
 * StorageInterface Interface
 */
interface StorageInterface
{
    /**
     * @param $filePath string
     * @param $name string
     * @param $options []
     * @return boolean
     */
    public function save($filePath, $name, $options = []);

    /**
     * @param $name string
     * @return boolean
     */
    public function delete($name);

    /**
     * @param $name string
     * @return boolean
     */
    public function fileExists($name);

    /**
     * @param $name string
     * @return string
     */
    public function getUrl($name);

    /**
     * @param $path string
     * @return string
     */
    public function getLocalPath($path);
}
