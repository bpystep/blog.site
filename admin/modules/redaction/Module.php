<?php
namespace admin\modules\redaction;

use common\modules\redaction\Module as CommonModule;

class Module extends CommonModule
{
    /* @var string */
    public $controllerNamespace = 'admin\modules\redaction\controllers';

    /* @var [] */
    public $urlRules = [
        //Категории
        'categories'                   => 'categories/index',
        'categories/<id:\d+>/<action>' => 'categories/<action>',
        //Теги
        'tags'                   => 'tags/index',
        'tags/<id:\d+>/<action>' => 'tags/<action>',
        //Новости
        'news'                   => 'posts/index',
        'news/<id:\d+>/<action>' => 'posts/<action>',
    ];
}
