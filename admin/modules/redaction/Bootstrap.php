<?php

namespace admin\modules\redaction;

use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;
use yii\web\GroupUrlRule;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        /* @var $module Module */
        if ($app->hasModule('redaction') && ($module = $app->getModule('redaction')) instanceof Module) {
            if ($app instanceof ConsoleApplication) {
                $module->controllerNamespace = 'admin\modules\redaction\controllers';
            }

            $configUrlRule = [
                'prefix' => $module->urlPrefix,
                'rules'  => $module->urlRules
            ];
            $app->urlManager->addRules([new GroupUrlRule($configUrlRule)], false);
        }
    }
}
