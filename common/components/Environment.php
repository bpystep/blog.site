<?php

namespace common\components;

use Exception;
use Yii;
use yii\helpers\ArrayHelper;

class Environment
{
    const ENV_DEV  = 'dev';
    const ENV_PROD = 'prod';

    const CONFIG_NAME_WEB     = 'web';
    const CONFIG_NAME_CONSOLE = 'console';

    protected string $envName = 'YII_ENV';

    protected array $validModes = [
        self::ENV_DEV,
        self::ENV_PROD
    ];

    protected array $configDir = [];

    protected ?string $mode = null;

    private static bool $isSafe = false;

    private bool $isWeb;

    private bool $isConsole;

    public string $yiiPath;

    public bool $yiiDebug;

    public string $yiiEnv;

    public array $aliases = [];

    public array $classMap = [];

    public array $web = [];

    public array $console = [];

    public function __construct(string $configDir, bool $isSafe, bool $isWeb, bool $isConsole, ?string $mode = null)
    {
        static::$isSafe = $isSafe;

        $this->isWeb = $isWeb;
        $this->isConsole = $isConsole;

        $this->setConfigDir($configDir);
        $this->setMode($mode);
        $this->setEnvironment();
    }

    /**
     * @throws Exception
     */
    protected function setConfigDir(string|array $configDir): void
    {
        $this->configDir = [];

        foreach ((array) $configDir as $k => $v) {
            $dir = rtrim($v, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            if (!is_dir($dir)) {
                throw new Exception("Invalid configuration directory '{$dir}'.");
            }

            $this->configDir[$k] = $dir;
        }
    }

    /**
     * @throws Exception
     */
    protected function setMode(?string $mode): void
    {
        if ($mode === null) {
            $mode = getenv($this->envName);
            if ($mode === false) {
                $mode = self::ENV_PROD;
            }
        }

        $mode = mb_strtolower($mode);
        if (!in_array($mode, $this->validModes, true)) {
            throw new Exception("Invalid environment mode supplied or selected: {$mode}");
        }

        $this->mode = $mode;
    }

    /**
     * @throws Exception
     */
    protected function setEnvironment(): void
    {
        $config = $this->getConfig();
        if (!is_readable($config['yiiPath'])) {
            throw new Exception("Invalid Yii framework path {$config['yiiPath']}.");
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

        $this->aliases  = $config['aliases']  ?? [];
        $this->classMap = $config['classMap'] ?? [];
    }

    public function setup(): void
    {
        $configName = $this->getConfigName();

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

        defined('YII_ENV') or define('YII_ENV', $this->yiiEnv);

        require(__DIR__ . '/Application.php');
        require($this->yiiPath);

        foreach ($this->aliases as $alias => $path) {
            Yii::setAlias($alias, $path);
        }

        // Merge class map.
        if (!empty($this->classMap)) {
            Yii::$classMap = ArrayHelper::merge(Yii::$classMap, $this->classMap);
        }
    }

    /**
     * @throws Exception
     */
    protected function getConfig(): array
    {
        $configName = $this->getConfigName();

        $envConfig = [];
        foreach ($this->configDir as $configDir) {
            $webConfigFile = "{$configDir}{$configName}.php";
            if (!file_exists($webConfigFile)) {
                throw new Exception("Cannot find web config file '{$webConfigFile}'.");
            }
            $webConfig = require($webConfigFile);
            if (is_array($webConfig)) {
                $envConfig = ArrayHelper::merge($envConfig, $webConfig);
            }

            $modeConfigFile = "{$configDir}{$configName}.{$this->mode}.php";
            if (!file_exists($modeConfigFile)) {
                //throw new Exception("Cannot find mode specific config file '{$modeConfigFile}'.");
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

    private function getConfigName(): ?string
    {
        if ($this->isWeb) {
            return self::CONFIG_NAME_WEB;
        } if ($this->isConsole) {
            return self::CONFIG_NAME_CONSOLE;
        }


        return null;
    }

    public function showDebug()
    {
        print '<div style="position: absolute; left: 0; width: 100%; height: 250px; overflow: auto;'
            . 'bottom: 0; z-index: 9999; color: #000; margin: 0; border-top: 1px solid #000;">'
            . '<pre style="margin: 0; background-color: #ddd; padding: 5px;">'
            . htmlspecialchars(print_r($this, true)) . '</pre></div>';
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        if (static::$isSafe) {
            $value = getenv($key);
        }

        $value = $value ?? $_ENV[$key] ?? $_SERVER[$key] ?? false;

        if ($value === '' || $value === false) {
            return $default;
        }

        return match (mb_strtolower($value)) {
            'true', '(true)'   => true,
            'false', '(false)' => false,
            default => $value
        };
    }
}
