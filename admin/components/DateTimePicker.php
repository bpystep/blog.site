<?php

namespace admin\components;

use dosamigos\datetimepicker\DateTimePicker as BaseDateTimePicker;
use yii\helpers\Html;

class DateTimePicker extends BaseDateTimePicker
{
    /* @var string */
    public $pickButtonIcon = 'far fa-clock';

    /**
     * @inheritdoc
     */
    public function run()
    {
        $input = $this->hasModel()
            ? Html::activeTextInput($this->model, $this->attribute, $this->options)
            : Html::textInput($this->name, $this->value, $this->options);

        echo $input;
        $this->registerClientScript();
    }
}
