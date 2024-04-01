<?php

namespace admin\widgets\Imperavi;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget as BaseWidget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

class Widget extends BaseWidget
{
    const PLUGINS_WITH_CALLBACKS = ['counter'];

    const PLUGINS_WITH_PARAMS = ['limiter', 'textexpander', 'imagemanager'];

    const PLUGINS_WITH_CSRF = ['imageUpload', 'fileUpload'];

    const PLUGINS_WITH_CSS = ['clips', 'alignment'];

    public ?Model $model = null;

    public ?string $attribute = null;

    public ?string $name = null;

    public ?string $value = null;

    public ?string $selector = null;

    public array $options = [];

    public array $settings = [];

    public string $lang = 'ru';

    public bool $disabled = false;

    public array $defaultSettings = [
        'imageResizable' => true,
        'imagePosition'  => true,
        //'toolbarFixed' => true
    ];

    public bool $imageUpload = false;

    public bool $fileUpload = false;

    public array $plugins = [
        'inlinestyle',
        'alignment',
        'table', /*'fullscreen',*/
        'fontsize',
        'fontcolor',
        'video',
        'imagemanager',
        'filemanager',
        'definedlinks',
        'limiter',
        'textexpander',
        'counter',
        'source'
    ];

    private bool $_renderTextarea = true;

    public function init(): void
    {
        if ($this->name === null && !$this->hasModel() && $this->selector === null) {
            throw new InvalidConfigException("Either 'name', or 'model' and 'attribute' properties must be specified.");
        }

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }

        if (!empty($this->plugins) && !is_array($this->plugins)) {
            throw new InvalidConfigException('The "plugins" property must be an array.');
        }

        if (!empty($this->settings) && !is_array($this->settings)) {
            throw new InvalidConfigException('The "settings" property must be an array.');
        } else {
            $this->settings = array_merge($this->defaultSettings, $this->settings);
            foreach (self::PLUGINS_WITH_CSRF as $item) {
                if (!empty($this->settings[$item])) {
                    $this->{$item} = true;
                }
            }
        }

        if ($this->selector === null) {
            $this->selector = '#' . $this->options['id'];
        } else {
            $this->_renderTextarea = false;
        }

        if (Yii::$app->request->enableCsrfValidation) {
            foreach (self::PLUGINS_WITH_CSRF as $item) {
                $this->settings[$item . 'Fields'][Yii::$app->request->csrfParam] = Yii::$app->request->getCsrfToken();
            }
        }

        parent::init();
    }

    public function run(): ?string
    {
        if (!$this->disabled) {
            $this->register();
        } else {
            $this->options['disabled'] = true;
        }

        if ($this->_renderTextarea === true) {
            if ($this->hasModel()) {
                return Html::activeTextarea($this->model, $this->attribute, $this->options);
            }

            return Html::textarea($this->name, $this->value, $this->options);
        }

        return null;
    }

    protected function register(): void
    {
        $this->registerDefaultParams();
        $this->registerDefaultCallbacks();
        $this->registerClientScripts();
    }

    protected function registerClientScripts(): void
    {
        $view = $this->getView();
        $selector = Json::encode($this->selector);

        /* @var $asset Asset */
        $asset = Yii::$container->get(Asset::class);
        $asset = $asset::register($view);
        $asset->addLanguage($this->lang);

        $plugins = [];
        if (!empty($this->plugins)) {
            $allArrays = array_merge(self::PLUGINS_WITH_CALLBACKS, self::PLUGINS_WITH_PARAMS);
            foreach ($this->plugins as $plugin) {
                if (
                    (in_array($plugin, self::PLUGINS_WITH_CALLBACKS) && !empty($this->settings['callbacks'][$plugin]))
                    || (in_array($plugin, self::PLUGINS_WITH_PARAMS) && !empty($this->settings[$plugin]))
                    || !in_array($plugin, $allArrays)
                ) {
                    $plugins[] = $plugin;
                }
            }
        }

        if (!empty($plugins)) {
            $this->plugins = $plugins;
            $asset->plugins = $plugins;
            $this->settings['plugins'] = $plugins;
            if (in_array('imagemanager', $plugins)) {
                $this->settings['imageManagerJson'] = $this->settings['imagemanager'];
                unset($this->settings['imagemanager']);
            }
        }

        $this->settings['lang'] = $this->lang;
        $view->registerJs('$(' . $selector . ').redactor(' . Json::encode($this->settings) . ');');
    }

    protected function registerDefaultCallbacks(): void
    {
        foreach (self::PLUGINS_WITH_CSRF as $item) {
            if ($this->{$item} && !isset($this->settings[$item . 'ErrorCallback'])) {
                $this->settings[$item . 'ErrorCallback'] = new JsExpression('function (response) {console.log(response.error);}');
            }
        }
    }

    protected function registerDefaultParams(): void
    {
        if ($this->imageUpload && !isset($this->settings['imageUpload'])) {
            $this->settings['imageUpload'] = Url::to(['/site/image-upload']);
        }
        if ($this->fileUpload && !isset($this->settings['fileUpload'])) {
            $this->settings['fileUpload'] = Url::to(['/site/file-upload']);
        }
    }

    protected function hasModel(): bool
    {
        return $this->model instanceof Model && !is_null($this->attribute);
    }
}
