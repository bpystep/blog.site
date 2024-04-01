<?php
namespace admin\modules\user;

use common\modules\user\Module as CommonModule;

class Module extends CommonModule
{
    /* @var string */
    public $controllerNamespace = 'admin\modules\user\controllers';

    /* @var [] */
    public $urlRules = [
        //Управление
        'update' => 'crud/update',
        //Сервисные
        'login'  => 'security/login',
        'logout' => 'security/logout'
    ];
}
