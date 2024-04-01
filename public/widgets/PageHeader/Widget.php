<?php

namespace public\widgets\PageHeader;

use public\assets\SmartyAsset;
use yii\base\Widget as BaseWidget;

class Widget extends BaseWidget
{
    /* @var $content string */
    public $content;
    /* @var $align string */
    protected $align = 'left';
    /* @var $margin string */
    protected $margin = 'xs';
    /* @var $breadcrumbs [] */
    protected $breadcrumbs = [];
    /* @var $breadcrumbsInverse boolean */
    protected $breadcrumbsInverse = false;
    /* @var $rotatorWords [] */
    protected $rotatorWords = [];
    /* @var $rotatorOptions [] */
    protected $rotatorOptions = [
        'data-delay' => 2000
    ];
    /* @var $tabs [] */
    protected $tabs = [];
    /* @var $light boolean */
    protected $light = false;
    /* @var $dark boolean */
    protected $dark;
    /* @var $alternate boolean */
    protected $alternate = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->dark = SmartyAsset::$isDark;

        if (isset($this->view->params['header']) && !empty($options = $this->view->params['header'])) {
            $options = array_merge($this->getDefaultOptions(), $options);
            $this->align = $options['align'];
            $this->margin = $options['margin'];
            $this->breadcrumbs = $options['breadcrumbs'];
            $this->breadcrumbsInverse = $options['breadcrumbsInverse'];
            $this->rotatorWords = $options['rotatorWords'];
            $this->rotatorOptions = $options['rotatorOptions'];
            $this->tabs = $options['tabs'];
            $this->light = $options['light'];
            $this->alternate = $options['alternate'];
        }
        if (!empty($this->view->params['breadcrumbs'])) {
            $this->breadcrumbs = $this->view->params['breadcrumbs'];
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('page-header', [
            'content'            => $this->content,
            'light'              => $this->light,
            'dark'               => $this->dark,
            'alternate'          => $this->alternate,
            'align'              => $this->align,
            'margin'             => $this->margin,
            'breadcrumbs'        => $this->breadcrumbs,
            'breadcrumbsInverse' => $this->breadcrumbsInverse,
            'tabs'               => $this->tabs,
            'rotatorWords'       => $this->rotatorWords,
            'rotatorOptions'     => $this->rotatorOptions
        ]);
    }

    /**
     * @return array
     */
    private function getDefaultOptions()
    {
        return [
            'align'              => 'left',
            'margin'             => 'xs',
            'breadcrumbs'        => [],
            'breadcrumbsInverse' => false,
            'rotatorWords'       => [],
            'rotatorOptions'     => [],
            'tabs'               => [],
            'light'              => false,
            'alternate'          => false
        ];
    }
}
