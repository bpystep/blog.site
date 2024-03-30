<?php
namespace common\modules\user;

use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    public int $rememberFor = 1209600; //2 недели
}
