<?php

namespace admin\components;

use admin\helpers\HtmlHelper;
use yii\base\InvalidConfigException;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav as BaseNav;
use yii\helpers\ArrayHelper;

class Nav extends BaseNav
{
    /* @var string */
    public $dropdownClass = 'admin\components\Dropdown';

    /* @var boolean */
    public $hasDropdown = false;

    /* @var boolean */
    public $dropdownRight = true;

    /* @var boolean */
    public $dropdownActivateLinks = true;

    /* @var boolean */
    public $encodeLabels = false;

    /**
     * @inheritdoc
     */
    public function renderItems()
    {
        $items = [];
        foreach ($this->items as $item) {
            if (isset($item['visible']) && !$item['visible']) {
                continue;
            }

            if (!$this->hasDropdown && !empty($item['items'])) {
                $this->hasDropdown = true;
            }

            $items[] = $this->renderItem($item);
        }

        if ($this->hasDropdown && $this->dropdownRight) {
            Html::addCssClass($this->options, 'd-flex');
        }

        return Html::tag('ul', implode("\n", $items), $this->options);
    }

    /**
     * @inheritdoc
     */
    public function renderItem($item)
    {
        if (is_string($item)) {
            return $item;
        }

        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }

        $encodeLabel = $item['encode'] ?? $this->encodeLabels;
        $label = $encodeLabel ? Html::encode($item['label']) : $item['label'];
        $options = ArrayHelper::getValue($item, 'options', []);
        $items = ArrayHelper::getValue($item, 'items');
        $url = ArrayHelper::getValue($item, 'url', '#');
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);
        $disabled = ArrayHelper::getValue($item, 'disabled', false);

        $active = $this->isItemActive($item);

        if (empty($items)) {
            $items = '';
            Html::addCssClass($options, ['widget' => 'nav-item']);
            Html::addCssClass($linkOptions, ['widget' => 'nav-link']);
            $this->activateLink($linkOptions, $disabled, $active);
            $link = Html::a($label, $url, $linkOptions);
        } else {
            $iconArrow = ArrayHelper::remove($options, 'arrowIcon', 'mdi mdi-chevron-down');

            $itemClasses = ['dropdown btn-group btn-group-vertical'];
            if ($iconRight = ArrayHelper::remove($item, 'iconRight', false)) {
                $itemClasses[] = 'btn-group-vertical--right';
            }
            if ($this->dropdownRight) {
                $itemClasses[] = 'ml-auto';
            }
            Html::addCssClass($options, ['widget' => implode(' ', $itemClasses)]);

            $linkOptions['data-bs-toggle'] = 'dropdown';
            $linkOptions['aria-expanded']  = 'false';
            Html::addCssClass($linkOptions, ['widget' => 'dropdown-toggle']);

            if (is_array($items)) {
                if (!$this->dropdownActivateLinks) {
                    foreach ($items as &$dropdownItem) {
                        $dropdownItem['active'] = false;
                    }
                }

                $items = $this->isChildActive($items, $active);
                $items = $this->renderDropdown($items, $item, $iconRight);
            }

            $this->activateLink($linkOptions, $disabled, $active);
            $link = HtmlHelper::button($label, $linkOptions, $iconArrow, $iconRight);
        }

        return Html::tag('li', $link . $items, $options);
    }

    /**
     * @param $iconRight boolean
     * @inheritdoc
     */
    protected function renderDropdown($items, $parentItem, $iconRight = false)
    {
        if ($iconRight) {
            $dropdownOptions = ArrayHelper::remove($parentItem, 'dropdownOptions', []);
            Html::addCssClass($dropdownOptions, 'text-end dropdown-menu-end');
            $parentItem['dropdownOptions'] = $dropdownOptions;
        }

        return parent::renderDropdown($items, $parentItem);
    }

    /**
     * @param $options []
     * @param $disabled boolean
     * @param $active boolean
     */
    protected function activateLink(&$options, $disabled, $active)
    {
        if ($disabled) {
            ArrayHelper::setValue($options, 'tabindex', '-1');
            ArrayHelper::setValue($options, 'aria-disabled', 'true');
            Html::addCssClass($options, ['disable' => 'disabled']);
        } elseif ($this->activateItems && $active) {
            Html::addCssClass($options, ['activate' => 'active']);
        }
    }
}
