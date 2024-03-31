<?php

namespace admin\components;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class DatePicker extends InputWidget
{
    /* @var string */
    public $value;

    /* @var string */
    public $language = 'ru';

    /* @var boolean */
    public $autocomplete = false;

    /* @var boolean */
    public $autoclose = true;

    /* @var string */
    public $format = 'dd.mm.yyyy';

    /* @var [] */
    public $clientOptions = [];

    /* @var [] */
    public $clientEvents = [];

    /* @var [] */
    public $containerOptions = [];

    /* @var string */
    public $template = '{input}{addon}';

    /* @var string */
    public $addon = '<i class="far fa-calendar-alt"></i>';

    /* @var string */
    public $leftArrow = '<i class="fas fa-long-arrow-alt-left"></i>';

    /* @var string */
    public $rightArrow = '<i class="fas fa-long-arrow-alt-right"></i>';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Html::addCssClass($this->options, 'form-control');
        Html::addCssClass($this->containerOptions, 'input-group date position-relative');
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!$this->autocomplete) {
            $this->options['autocomplete'] = 'off';
        }

        if ($this->value) {
            $this->options['value'] = $this->value;
        }

        $input = $this->hasModel()
            ? Html::activeTextInput($this->model, $this->attribute, $this->options)
            : Html::textInput($this->name, $this->value, $this->options);

        if ($this->addon) {
            $addon = Html::tag('span', $this->addon, ['class' => 'input-group-addon input-group-text']);
            $input = strtr($this->template, ['{input}' => $input, '{addon}' => $addon]);
            $input = Html::tag('div', $input, $this->containerOptions);
        }

        echo $input;

        $this->registerClientScript();
    }

    /**
     * Registers required script for the plugin to work as DatePicker
     */
    public function registerClientScript()
    {
        $js = [];
        $view = $this->getView();

        if ($this->language !== 'en') {
            $this->clientOptions['language'] = $this->language;
        }

        if ($this->autoclose) {
            $this->clientOptions['autoclose'] = true;
        }

        if ($this->format) {
            $this->clientOptions['format'] = $this->format;
        }

        if ($this->leftArrow) {
            $this->clientOptions['templates']['leftArrow'] = $this->leftArrow;
        }
        if ($this->rightArrow) {
            $this->clientOptions['templates']['rightArrow'] = $this->rightArrow;
        }

        $id = $this->options['id'];
        $selector = ";jQuery('#$id')";

        if ($this->addon) {
            $selector .= ".parent()";
        }

        $options = !empty($this->clientOptions) ? Json::encode($this->clientOptions) : '';

        $js[] = "$selector.datepicker($options);";

        if (!empty($this->clientEvents)) {
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = "$selector.on('$event', $handler);";
            }
        }
        $view->registerJs(implode("\n", $js));
    }
}
