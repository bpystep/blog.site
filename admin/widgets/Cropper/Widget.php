<?php

namespace admin\widgets\Cropper;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget as BaseWidget;
use yii\db\ActiveRecord;

class Widget extends BaseWidget
{
    /* @var ActiveRecord */
    public $model;

    /* @var string */
    public $attribute;

    /* @var string */
    public $name;

    /* @var float */
    public $ratio = 1;

    /* @var integer */
    public $previewWidth;

    /* @var integer */
    public $previewHeight;

    /* @var string*/
    public $label = 'Изменить логотип';

    /* @var integer */
    public $indexAttribute;

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        if (empty($this->model) || empty($this->attribute)) {
            throw new InvalidConfigException('The "model" and "attribute" options is required.');
        }
        if (!$this->label) {
            $this->label = Yii::t('common', 'Изменить логотип');
        }

        Asset::register($this->view);
    }

    public function run() {
        return $this->render('cropper', [
            'model'          => $this->model,
            'attribute'      => $this->attribute,
            'name'           => $this->name,
            'ratio'          => $this->ratio,
            'previewWidth'   => $this->previewWidth,
            'previewHeight'  => $this->previewHeight,
            'label'          => $this->label,
            'indexAttribute' => $this->indexAttribute
        ]);
    }
}
