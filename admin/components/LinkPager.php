<?php

namespace admin\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap4\LinkPager as BaseLinkPager;

class LinkPager extends BaseLinkPager
{
    /* @var boolean */
    public $disableCurrentPageButton = true;

    /* @var string|boolean */
    public $firstPageLabel = '<i class="far fa-arrow-alt-circle-left"></i>';

    /* @var string|boolean */
    public $prevCountPageLabel = '<i class="fas fa-angle-double-left"></i>';

    /* @var string|boolean */
    public $prevPageLabel = '<i class="fas fa-angle-left"></i>';

    /* @var string|boolean */
    public $nextPageLabel = '<i class="fas fa-angle-right"></i>';

    /* @var string|boolean */
    public $nextCountPageLabel = '<i class="fas fa-angle-double-right"></i>';

    /* @var string|boolean */
    public $lastPageLabel = '<i class="far fa-arrow-alt-circle-right"></i>';

    /* @var integer */
    public $prevCountButtonPages = 10;

    /* @var string */
    public $prevCountPageCssClass = 'prev-count';

    /* @var integer */
    public $nextCountButtonPages = 10;

    /* @var string */
    public $nextCountPageCssClass = 'next-count';

    public $tooltip = true;

    /* @var string */
    public $firstPageTooltipLabel = 'на первую страницу';

    /* @var string */
    public $prevCountPageTooltipLabel = 'на {count,plural,=1{# страницу} one{# страницу} few{# страницы} many{# страниц} other{# страницу}} назад';

    /* @var string */
    public $prevPageTooltipLabel = 'на предыдущую страницу';

    /* @var string */
    public $nextPageTooltipLabel = 'на следующую страницу';

    /* @var string */
    public $nextCountPageTooltipLabel = 'на {count,plural,=1{# страницу} one{# страницу} few{# страницы} many{# страниц} other{# страницу}} вперед';

    /* @var string */
    public $lastPageTooltipLabel = 'на последнюю страницу';

    /* @var array */
    public $navOptions = [];

    /* @var array */
    private $defaultOptions = ['class' => 'pagination mb-0'];

    /* @var array */
    private $defaultLinkOptions = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->defaultLinkOptions = $this->linkOptions;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->registerLinkTags) {
            $this->registerLinkTags();
        }

        $tag = ArrayHelper::remove($this->navOptions, 'tag', 'nav');
        if ($class = ArrayHelper::remove($this->navOptions, 'class')) {
            Html::addCssClass($this->navOptions, $class);
        }

        $html = Html::beginTag($tag, $this->navOptions);
        $html .= $this->renderPageButtons();
        $html .= Html::endTag($tag);

        return $html;
    }

    /**
     * @inheritdoc
     */
    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $buttons = [];
        $currentPage = $this->pagination->getPage();

        //Рендерит кнопку на первую страницу
        $firstPageLabel = $this->firstPageLabel === true ? '1' : $this->firstPageLabel;
        if ($firstPageLabel !== false) {
            $buttons[] = $this->renderFirstPageButton($currentPage, $firstPageLabel);
        }

        //Рендерит кнопку на ($currentPage - $this->prevCountButtonPages) страницу
        if ($this->prevCountPageLabel !== false) {
            $buttons[] = $this->renderPrevCountPageButton($currentPage);
        }

        //Рендерит кнопку на предыдущую страницу
        if ($this->prevPageLabel !== false) {
            $buttons[] = $this->renderPrevPageButton($currentPage);
        }

        //Рендерит $this->maxButtonCount кнопок страниц
        [$beginPage, $endPage] = $this->getPageRange();
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->renderPageButton($i + 1, $i, null, $this->disableCurrentPageButton && $i == $currentPage, $i == $currentPage);
        }

        //Рендерит кнопку на следующую страницу
        if ($this->nextPageLabel !== false) {
            $buttons[] = $this->renderNextPageButton($currentPage, $pageCount);
        }

        //Рендерит кнопку на ($currentPage + $this->nextCountButtonPages) страницу
        if ($this->nextCountPageLabel !== false) {
            $buttons[] = $this->renderNextCountPageButton($currentPage, $pageCount);
        }

        //Рендерит кнопку на последнюю страницу
        $lastPageLabel = $this->lastPageLabel === true ? $pageCount : $this->lastPageLabel;
        if ($lastPageLabel !== false) {
            $buttons[] = $this->renderLastPageButton($currentPage, $pageCount, $lastPageLabel);
        }

        if ($class = ArrayHelper::remove($this->options, 'class')) {
            Html::addCssClass($this->defaultOptions, $class);
        }

        return Html::tag(ArrayHelper::remove($this->options, 'tag', 'ul'), implode("\n", $buttons), array_merge($this->defaultOptions, $this->options));
    }

    /**
     * @param $currentPage integer
     * @param $firstPageLabel string
     * @return string
     */
    protected function renderFirstPageButton($currentPage, $firstPageLabel)
    {
        return $this->renderPageButton($firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false, $this->getTooltip(Yii::t('*', $this->firstPageTooltipLabel)));
    }

    /**
     * @param $currentPage integer
     * @return string
     */
    protected function renderPrevCountPageButton($currentPage)
    {
        if (($page = $currentPage - $this->prevCountButtonPages) < 0) {
            $page = 0;
        }

        return $this->renderPageButton($this->prevCountPageLabel, $page, $this->prevCountPageCssClass, $page <= 0, false, $this->getTooltip(Yii::t('*', $this->prevCountPageTooltipLabel, ['count' => $this->prevCountButtonPages])));
    }

    /**
     * @param $currentPage integer
     * @return string
     */
    protected function renderPrevPageButton($currentPage)
    {
        if (($page = $currentPage - 1) < 0) {
            $page = 0;
        }

        return $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false, $this->getTooltip(Yii::t('*', $this->prevPageTooltipLabel)));
    }

    /**
     * @param $currentPage integer
     * @param $pageCount integer
     * @return string
     */
    protected function renderNextPageButton($currentPage, $pageCount)
    {
        if (($page = $currentPage + 1) >= $pageCount - 1) {
            $page = $pageCount - 1;
        }

        return $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false, $this->getTooltip(Yii::t('*', $this->nextPageTooltipLabel)));
    }

    /**
     * @param $currentPage integer
     * @param $pageCount integer
     * @return string
     */
    protected function renderNextCountPageButton($currentPage, $pageCount)
    {
        if (($page = $currentPage + $this->nextCountButtonPages) >= $pageCount - 1) {
            $page = $pageCount - 1;
        }

        return $this->renderPageButton($this->nextCountPageLabel, $page, $this->nextCountPageCssClass, $currentPage >= $pageCount - $this->nextCountButtonPages - 1, false, $this->getTooltip(Yii::t('*', $this->nextCountPageTooltipLabel, ['count' => $this->nextCountButtonPages])));
    }

    /**
     * @param $currentPage integer
     * @param $pageCount integer
     * @param $lastPageLabel string
     * @return string
     */
    protected function renderLastPageButton($currentPage, $pageCount, $lastPageLabel)
    {
        return $this->renderPageButton($lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false, $this->getTooltip(Yii::t('*', $this->lastPageTooltipLabel)));
    }

    /**
     * @inheritdoc
     * @param array $linkOptions []
     */
    protected function renderPageButton($label, $page, $class, $disabled, $active, $linkOptions = [])
    {
        if ($class = ArrayHelper::remove($linkOptions, 'class')) {
            Html::addCssClass($this->defaultLinkOptions, $class);
        }
        $this->linkOptions = ArrayHelper::merge($this->defaultLinkOptions, $linkOptions);

        return parent::renderPageButton($label, $page, $class, $disabled, $active);
    }

    /**
     * @param $label string
     * @return array
     */
    private function getTooltip($label)
    {
        return $this->tooltip ? ['title' => str_replace(' ', '&nbsp;', $label), 'class' => 'add-tooltip', 'data' => ['placement' => 'top', 'html' => 'true']] : [];
    }
}
