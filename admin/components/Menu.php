<?php
namespace admin\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Menu extends Widget
{
    /* @var [] */
    public $items = [];

    /* @var boolean */
    public $encodeLabels = true;

    /* @var boolean */
    public $activateItems = true;

    /* @var boolean */
    public $activateParents = true;

    /* @var boolean */
    public $checkModuleOnly = false;

    /* @var string */
    public $route;

    /* @var [] */
    public $params;

    /* @var integer */
    private static $dropdownsCount = 0;

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }
        $this->options['id'] = 'side-menu';
    }

    /**
     * @inheritdoc
     */
    public function run() {
        return $this->renderItems();
    }

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function renderItems() {
        $items = [];
        foreach ($this->items as $item) {
            if (isset($item['visible']) && !$item['visible']) {
                continue;
            }
            $items[] = $this->renderItem($item);
        }

        return Html::tag('ul', implode("\n", $items), $this->options);
    }

    /**
     * @param $item
     * @param $isSub boolean
     * @return string
     * @throws InvalidConfigException
     */
    public function renderItem($item, $isSub = false) {
        if (is_string($item)) {
            return $item;
        }
        if (!isset($item['label'])) {
            throw new InvalidConfigException('The "label" option is required.');
        }

        $options     = ArrayHelper::getValue($item, 'options', []);
        $items       = ArrayHelper::getValue($item, 'items');
        $url         = ArrayHelper::getValue($item, 'url', '#');
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);

        $encodeLabel = $item['encode'] ?? $this->encodeLabels;
        $label = $encodeLabel ? Html::encode($item['label']) : $item['label'];
        if (!$isSub) {
            $label = Html::tag('span', $label);
        }
        if (isset($item['icon'])) {
            $label = Html::tag('i', '', ['class' => $item['icon']]) . $label;
        }

        if (isset($item['active'])) {
            $active = ArrayHelper::remove($item, 'active', false);
        } else {
            $active = $this->isItemActive($item);
        }
        $childActive = false;

        if (empty($items)) {
            $items = '';
        } else {
            $label .= Html::tag('span', null, ['class' => 'menu-arrow']);
            $id = 'side-menu-dropdown-' . static::$dropdownsCount++;
            $url = "#{$id}";
            $linkOptions['data-bs-toggle'] = 'collapse';
            $linkOptions['aria-expanded'] = 'false';
            $linkOptions['aria-controls'] = $id;
            if (!$isSub) {
                Html::addCssClass($linkOptions, 'nav-link');
            }

            if (is_array($items)) {
                if ($this->activateItems) {
                    $items = $this->isChildActive($items, $childActive);
                }
                $items = $this->renderSubItems($items, $id, $childActive, ArrayHelper::getValue($item, 'dropDownOptions', []));
            }
        }

        if ($this->activateItems) {
            if ($childActive || $active) {
                Html::addCssClass($options, 'menuitem-active');
            }
            if ($active && !$childActive || ($this->activateParents && $childActive)) {
                Html::addCssClass($options, 'menuitem-active');
                $linkOptions['aria-expanded'] = 'true';
            }
        }

        if (!$isSub) {
            Html::addCssClass($options, 'nav-item');
        }

        return Html::tag('li', Html::a($label, $url, $linkOptions) . $items, $options);
    }

    /**
     * @param $items []
     * @param $childActive boolean
     * @param $options []
     * @return string
     * @throws InvalidConfigException
     */
    protected function renderSubItems($items, $id, $childActive, $options = []) {
        $lines = [];
        foreach ($items as $item) {
            if (isset($item['visible']) && !$item['visible']) {
                continue;
            }
            $lines[] = $this->renderItem($item, true);
        }

        Html::addCssClass($options, 'nav-second-level');

        return Html::tag('div', Html::tag('ul', implode("\n", $lines), $options), [
            'id'    => $id,
            'class' => $childActive ? 'collapse show' : 'collapse'
        ]);
    }

    /**
     * @param $items []
     * @param $active boolean
     * @return array
     */
    protected function isChildActive($items, &$active) {
        foreach ($items as $i => $child) {
            if (ArrayHelper::remove($items[$i], 'active', false) || $this->isItemActive($child)) {
                Html::addCssClass($items[$i]['options'], 'menuitem-active');
                $active = true;
            }
        }

        return $items;
    }

    /**
     * @param $item
     * @return boolean
     */
    protected function isItemActive($item) {
        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
            $route = $item['url'][0];
            if (!$this->checkModuleOnly) {
                if ($route[0] !== '/' && Yii::$app->controller) {
                    $route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
                }
                if (ltrim($route, '/') !== $this->route) {
                    return false;
                }
            } else {
                preg_match('/^\/([a-z]+)\//', $route, $matches);
                $route = $matches[1];
                if ($route !== Yii::$app->controller->module->getUniqueId()) {
                    return false;
                }
            }
            unset($item['url']['#']);
            if (count($item['url']) > 1) {
                $params = $item['url'];
                unset($params[0]);
                foreach ($params as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                        return false;
                    }
                }
            }

            return true;
        }

        return false;
    }
}
