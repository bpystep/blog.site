<?php
namespace admin\modules\user;

use common\modules\user\models\User;
use Yii;
use yii\base\BootstrapInterface;
use yii\web\GroupUrlRule;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app) {
        /* @var $module Module */
        if ($app->hasModule('user') && ($module = $app->getModule('user')) instanceof Module) {
            Yii::$container->set('yii\web\User', [
                'enableAutoLogin' => true,
                'loginUrl'        => ['/user/security/login'],
                'identityClass'   => User::class
            ]);

            $configUrlRule = [
                'prefix' => $module->urlPrefix,
                'rules'  => $module->urlRules,
            ];
            $app->urlManager->addRules([new GroupUrlRule($configUrlRule)], false);
        }
    }
}
