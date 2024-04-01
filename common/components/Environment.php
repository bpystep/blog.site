<?php

namespace common\components;

use yii\helpers\ArrayHelper;
use Exception;

class Environment
{
    const ENV_DEV  = 'dev';
    const ENV_PROD = 'prod';

    /* @var string */
    protected $envName = 'YII_ENV';

    /* @var string[] */
    protected $validModes = [
        self::ENV_DEV, self::ENV_PROD
    ];

    /* @var string[] */
    protected $configDir = [];

    /* @var string */
    protected $mode;

    /* @var boolean */
    private static $isSafe = false;

    /* @var boolean */
    private $isWeb;

    /* @var boolean */
    private $isConsole;

    /* @var string */
    public $yiiPath;

    /* @var boolean */
    public $yiiDebug;

    /* @var string */
    public $yiiEnv;

    /* @var array */
    public $aliases = [];

    /* @var array */
    public $classMap = [];

    /* @var array */
    public $web = [];

    /* @var array */
    public $console = [];

    /**
     * @param string|string[] $configDir
     * @param string $mode
     * @throws Exception
     */
    public function __construct($configDir, $isSafe, $isWeb, $isConsole, $mode = null)
    {
        static::$isSafe = $isSafe;
        $this->isWeb = $isWeb;
        $this->isConsole = $isConsole;

        $this->setConfigDir($configDir);
        $this->setMode($mode);
        $this->setEnvironment();
    }

    /**
     * @param $configDir string|string[]
     * @throws Exception
     */
    protected function setConfigDir($configDir)
    {
        $this->configDir = [];
        foreach ((array) $configDir as $k => $v) {
            $dir = rtrim($v, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            if (!is_dir($dir)) {
                throw new \Exception("Invalid configuration directory '{$dir}'.");
            }
            $this->configDir[$k] = $dir;
        }
    }

    /**
     * @param $mode string
     * @throws Exception
     */
    protected function setMode($mode)
    {
        if ($mode === null) {
            $mode = getenv($this->envName);
            if ($mode === false) {
                $mode = self::ENV_PROD;
            }
        }

        $mode = mb_strtolower($mode);
        if (!in_array($mode, $this->validModes, true)) {
            throw new \Exception("Invalid environment mode supplied or selected: {$mode}");
        }

        $this->mode = $mode;
    }

    /**
     * Sets the environment and configuration for the selected mode.
     * @throws Exception
     */
    protected function setEnvironment()
    {
        $config = $this->getConfig();
        if (!is_readable($config['yiiPath'])) {
            throw new \Exception("Invalid Yii framework path {$config['yiiPath']}.");
        }

        $this->yiiPath  = $config['yiiPath'];
        $this->yiiDebug = $config['yiiDebug'] ?? false;
        $this->yiiEnv   = $config['yiiEnv']   ?? $this->mode;

        if (!empty($this->web = $config['web'] ?? [])) {
            $this->web['params']['env'] = $this->mode;
        }

        if (!empty($this->console = $config['console'] ?? [])) {
            $this->console['params']['env'] = $this->mode;
        }

        $this->aliases  = $config['aliases'] ?? [];
        $this->classMap = $config['classMap'] ?? [];
    }

    /**
     * Defines Yii constants, includes base Yii class, sets aliases and merges class map.
     */
    public function setup()
    {
        $configName = $this->getConfigName();

        /**
         * This constant defines whether the application should be in debug mode or not.
         */
        defined('YII_DEBUG') or define('YII_DEBUG', $this->yiiDebug);
        if ($this->yiiDebug && $this->yiiEnv != self::ENV_PROD) {
            $this->{$configName}['bootstrap'][] = 'debug';
            $this->{$configName}['modules']['debug'] = [
                'class'      => 'yii\debug\Module',
                'panels'     => [
                    'queue' => 'yii\queue\debug\Panel'
                ],
                'allowedIPs' => ['*']
            ];
            $this->{$configName}['components']['log']['tracelevel'] = 3;
        }

        /**
         * This constant defines in which environment the application is running.
         * The value could be 'prod' (production) or 'dev' (development).
         */
        defined('YII_ENV') or define('YII_ENV', $this->yiiEnv);

        // Include common/Application.
        require(__DIR__ . '/../Application.php');

        // Include Yii.
        require($this->yiiPath);

        // Set aliases.
        foreach ($this->aliases as $alias => $path) {
            \Yii::setAlias($alias, $path);
        }

        // Merge class map.
        if (!empty($this->classMap)) {
            \Yii::$classMap = ArrayHelper::merge(\Yii::$classMap, $this->classMap);
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getConfig()
    {
        $configName = $this->getConfigName();

        $envConfig = [];
        foreach ($this->configDir as $configDir) {
            $webConfigFile = "{$configDir}{$configName}.php";
            if (!file_exists($webConfigFile)) {
                throw new \Exception("Cannot find web config file '{$webConfigFile}'.");
            }
            $webConfig = require($webConfigFile);
            if (is_array($webConfig)) {
                $envConfig = ArrayHelper::merge($envConfig, $webConfig);
            }

            $modeConfigFile = "{$configDir}{$configName}.{$this->mode}.php";
            if (!file_exists($modeConfigFile)) {
                //throw new \Exception("Cannot find mode specific config file '{$modeConfigFile}'.");
            } else {
                $modeConfig = require($modeConfigFile);
                if (is_array($modeConfig)) {
                    $envConfig = ArrayHelper::merge($envConfig, $modeConfig);
                }
            }

            $localConfigFile = "{$configDir}{$configName}.local.php";
            if (file_exists($localConfigFile)) {
                $localConfig = require($localConfigFile);
                if (is_array($localConfig)) {
                    $envConfig = ArrayHelper::merge($envConfig, $localConfig);
                }
            }
        }

        return $envConfig;
    }

    /**
     * @return string|null
     */
    private function getConfigName()
    {
        return $this->isWeb ? 'web' : (
            $this->isConsole ? 'console' : null
        );
    }

    /**
     * Show current Environment class values.
     */
    public function showDebug()
    {
        print '<div style="position: absolute; left: 0; width: 100%; height: 250px; overflow: auto;'
            . 'bottom: 0; z-index: 9999; color: #000; margin: 0; border-top: 1px solid #000;">'
            . '<pre style="margin: 0; background-color: #ddd; padding: 5px;">'
            . htmlspecialchars(print_r($this, true)) . '</pre></div>';
    }

    /**
     * @param $key string
     * @param $default mixed
     * @return array|bool|false|mixed|string|null
     */
    public static function get($key, $default = null)
    {
        if (static::$isSafe) {
            $value = getenv($key);
        }
        $value = $value ?? $_ENV[$key] ?? $_SERVER[$key] ?? false;

        if ($value === '' || $value === false) {
            return $default;
        }

        switch (mb_strtolower($value)) {
            case 'true'   :
            case '(true)' : return true;
            case 'false'  :
            case '(false)': return false;
        }

        return $value;
    }
}
