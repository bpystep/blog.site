<?php
namespace admin\helpers;

use yii\base\Model;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class HtmlHelper
{
    const CHECKBOX_INPUT_CLASS = 'form-check-input';
    const CHECKBOX_LABEL_CLASS = 'form-check-label';
    const CHECKBOX_TEMPLATE    = '<div class="form-check">{input}{label}</div>';

    const RADIO_INPUT_CLASS = 'form-check-input';
    const RADIO_LABEL_CLASS = 'form-check-label';
    const RADIO_TEMPLATE    = '<div class="form-check">{input}{label}</div>';

    /* @var integer */
    public static int $checkboxIds = 0;

    /**
     * @param ActiveForm $form
     * @param Model $model
     * @param string $attribute
     * @param array $options
     * @param bool $enclosedByLabel
     * @return string
     */
    public static function checkboxForm(ActiveForm $form, Model $model, string $attribute, array $options = [], bool $enclosedByLabel = false): string
    {
        return $form->field($model, $attribute)->checkbox(self::checkboxOptions($options), $enclosedByLabel);
    }

    /**
     * @param ActiveRecord $model
     * @param string $attribute
     * @param array $options
     * @return string
     */
    public static function checkboxActive(ActiveRecord $model, string $attribute, array $options = []): string
    {
        return str_replace(['{input}', '{label}'], [Html::activeCheckbox($model, $attribute, self::checkboxOptions($options)), ''], self::CHECKBOX_TEMPLATE);
    }

    /**
     * @param string $name
     * @param bool $checked
     * @param string $label
     * @param array $options
     * @param array $labelOptions
     * @return string
     */
    public static function checkbox(string $name, bool $checked = false, string $label = '', array $options = [], array $labelOptions = []): string
    {
        Html::addCssClass($options, self::CHECKBOX_INPUT_CLASS);
        $options['id'] = ArrayHelper::remove($options, 'id', 'checkbox-' . ++static::$checkboxIds);
        $input = Html::checkbox($name, $checked, $options);

        if ($label) {
            Html::addCssClass($labelOptions, self::CHECKBOX_LABEL_CLASS);
            $label = Html::label($label, $options['id'], $labelOptions);
        }

        return implode('', [
            Html::hiddenInput($name, 0),
            str_replace(['{input}', '{label}'], [$input, $label], self::CHECKBOX_TEMPLATE)
        ]);
    }

    /**
     * @param array $options
     * @return array
     */
    private static function checkboxOptions(array $options): array
    {
        $options['template'] = self::CHECKBOX_TEMPLATE;
        $labelOptions = ArrayHelper::remove($options, 'labelOptions', []);
        Html::addCssClass($options, self::CHECKBOX_INPUT_CLASS);
        Html::addCssClass($labelOptions, self::CHECKBOX_LABEL_CLASS);
        $options['labelOptions'] = $labelOptions;
        if (!empty($options['label'])) {
            $options['label'] = Html::label($options['label'], ArrayHelper::remove($labelOptions, 'for', $options['id'] ?? null), $labelOptions);
        }

        return $options;
    }

    /**
     * @param string $name
     * @param bool $checked
     * @param string $label
     * @param array $options
     * @param array $labelOptions
     * @return string
     */
    public static function radio(string $name, bool $checked = false, string $label = '', array $options = [], array $labelOptions = []): string
    {
        Html::addCssClass($options, self::RADIO_INPUT_CLASS);
        $options['id'] = ArrayHelper::remove($options, 'id', 'checkbox-' . ++static::$checkboxIds);
        $input = Html::radio($name, $checked, $options);

        if ($label) {
            Html::addCssClass($labelOptions, self::RADIO_LABEL_CLASS);
            $label = Html::label($label, $options['id'], $labelOptions);
        }

        return str_replace(['{input}', '{label}'], [$input, $label], self::RADIO_TEMPLATE);
    }

    /**
     * @param array $options
     * @return array
     */
    private static function radioOptions(array $options): array
    {
        $options['template'] = self::CHECKBOX_TEMPLATE;
        $labelOptions = ArrayHelper::remove($options, 'labelOptions', []);
        Html::addCssClass($options, self::CHECKBOX_INPUT_CLASS);
        Html::addCssClass($labelOptions, self::CHECKBOX_LABEL_CLASS);
        $options['labelOptions'] = $labelOptions;

        return $options;
    }

    /**
     * @param string $content
     * @param array $options
     * @param string|false|null $icon
     * @param string|false|null $additionalIcon
     * @param boolean $inverseIcon
     * @param string|false|null $additionalIcon
     * @return string
     */
    public static function button(string $content, array $options = [], $icon = null, bool $inverseIcon = false, $additionalIcon = null): string
    {
        return Html::button(self::buttonContent($content, $icon, $inverseIcon, $additionalIcon), self::buttonOptions($options));
    }

    /**
     * @param string $content
     * @param array $options
     * @param string|false|null $icon
     * @param boolean $inverseIcon
     * @param string|false|null $additionalIcon
     * @return string
     */
    public static function buttonSubmit(string $content, array $options = [], $icon = null, bool $inverseIcon = false, $additionalIcon = null): string
    {
        return Html::submitButton(self::buttonContent($content, $icon, $inverseIcon, $additionalIcon), self::buttonOptions($options));
    }

    /**
     * @param string $content
     * @param array $options
     * @param string|array $url
     * @param string|false|null $icon
     * @param boolean $inverseIcon
     * @param string|false|null $additionalIcon
     * @return string
     */
    public static function buttonLink(string $content, $url, array $options = [], $icon = null, bool $inverseIcon = false, $additionalIcon = null): string
    {
        return Html::a(self::buttonContent($content, $icon, $inverseIcon, $additionalIcon), $url, self::buttonOptions($options));
    }

    /**
     * @param string $content
     * @param array $options
     * @param string|array $url
     * @param string|false|null $icon
     * @return string
     */
    public static function a(string $content, $url, array $options = [], $icon = null, bool $inverseIcon = false, $additionalIcon = null): string
    {
        return self::buttonLink($content, $url, $options, $icon, $inverseIcon, $additionalIcon);
    }

    /**
     * @param string $content
     * @param string|false|null $icon
     * @param boolean $inverseIcon
     * @param string|false|null $additionalIcon
     * @return string
     */
    public static function buttonContent(string $content, $icon, bool $inverseIcon, $additionalIcon): string
    {
        if (!$icon) {
            return $content;
        }

        $content = array_filter([
            Html::tag('span', Html::tag('i', null, ['class' => $icon]), ['class' => $inverseIcon ? 'btn-label-right' : 'btn-label']),
            $content,
            $additionalIcon ? Html::tag('span', Html::tag('i', null, ['class' => $additionalIcon]), ['class' => $inverseIcon ? 'btn-label' : 'btn-label-right']) : null
        ]);

        return implode('', $inverseIcon ? array_reverse($content) : $content);
    }

    /**
     * @param array $options
     * @return array
     */
    public static function buttonOptions(array $options): array
    {
        Html::addCssClass($options, 'waves-effect');
        Html::addCssClass($options, 'waves-light');

        return $options;
    }
}
