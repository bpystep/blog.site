<?php

namespace admin\components;

use admin\helpers\HtmlHelper;
use Exception;
use yii\base\InvalidConfigException;
use yii\base\Widget as BaseWidget;
use yii\bootstrap4\Dropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ButtonDropdown extends BaseWidget
{
    /* @var string*/
    public $label;

    /* @var array */
    public $options = [];

    /* @var string */
    public $icon = 'mdi mdi-chevron-down';

    /* @var boolean */
    public $isRight = true;

    /* @var array */
    public $dropDownOptions = [];

    /* @var array */
    public $buttonOptions = [];

    /* @var string */
    public $buttonIcon;

    /* @var array */
    public $items = [];

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init(){
        parent::init();

        if (!$this->label) {
            throw new InvalidConfigException('Options "label" must be required');
        }
    }

    /**
     * @inheritdoc
     * @inheritdoc
     */
    public function run(){
        $html = implode('', [
            $this->renderButton(),
            $this->renderDropdown()
        ]);

        Html::addCssClass($this->options, 'dropdown btn-group btn-group-vertical');
        if ($this->isRight) {
            Html::addCssClass($this->options, 'btn-group-vertical--right');
        }
        $tag = ArrayHelper::remove($this->options, 'tag', 'div');

        return Html::tag($tag, $html, $this->options);
    }

    /**
     * @return string
     */
    protected function renderButton(){
        $this->buttonOptions = array_merge([
            'data-bs-toggle' => 'dropdown',
            'aria-expanded'  => 'false'
        ], $this->buttonOptions);
        Html::addCssClass($this->buttonOptions, 'dropdown-toggle');

        return HtmlHelper::button($this->label, $this->buttonOptions, $this->icon, $this->isRight, $this->buttonIcon);
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function renderDropdown(){
        if ($this->isRight) {
            Html::addCssClass($this->dropDownOptions, 'dropdown-menu-end text-end');
        }

        return Dropdown::widget([
            'encodeLabels' => false,
            'options'      => $this->dropDownOptions,
            'items'        => $this->items
        ]);
    }
}
