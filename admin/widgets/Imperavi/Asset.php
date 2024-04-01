<?php
namespace admin\widgets\Imperavi;

use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    /* @var string */
    public $sourcePath = '@admin/widgets/Imperavi/src';

    /* @var [] */
    public $plugins = [];

    /* @inheritdoc */
    public $css = ['redactor.min.css'];

    /* @inheritdoc */
    public $js = ['redactor.min.js'];

    /* @inheritdoc */
    public $depends = ['admin\assets\AdminAsset'];

    /* Register asset bundle plugins. */
    public function registerAssetFiles($view)
    {
        if (!empty($this->plugins)) {
            foreach ($this->plugins as $plugin) {
                $path = 'ext/' . $plugin . '/';
                if (in_array($plugin, Widget::PLUGINS_WITH_CSS)) {
                    $this->css[] = $path . $plugin . '.css';
                }
                $this->js[] = $path . $plugin . '.js';
            }
        }

        parent::registerAssetFiles($view);
    }

    /**
     * @param $language string
     */
    public function addLanguage($language = 'en')
    {
        $this->js[] = 'lang/' . $language . '.js';
    }
}
