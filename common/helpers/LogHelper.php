<?php

namespace common\helpers;

use Yii;
use yii\base\Component;
use yii\helpers\FileHelper;

class LogHelper extends Component
{
    const SEPARATOR = '----------------------------------------------------------------------------------';

    public string $logPath;

    public string $logFile;

    public function init()
    {
        parent::init();

        $this->logPath = Yii::getAlias('@public/log');
        $this->logFile = $this->logPath . DIRECTORY_SEPARATOR . 'log.log';

        FileHelper::createDirectory($this->logPath);
    }

    public function write(string $text): void
    {
        $date = date('d.m.Y H:i:s');
        $timestamp = time();

        file_put_contents($this->logFile, '[' . $date . '] - [' . $timestamp . '] - ' . $text . PHP_EOL, FILE_APPEND);
    }

    public function start(): void
    {
        file_put_contents($this->logFile, self::SEPARATOR . PHP_EOL, FILE_APPEND);
    }

    public function end(): void
    {
        file_put_contents($this->logFile, self::SEPARATOR . PHP_EOL, FILE_APPEND);
    }
}
