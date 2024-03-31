<?php
namespace admin\controllers;

use common\controllers\DefaultController as DefaultCommonController;
use common\models\Settings;
use common\modules\user\models\User;
use Yii;
use yii\web\ForbiddenHttpException;

class DefaultController extends DefaultCommonController
{
    public $layout = '/base';

    public ?User $user = null;

    public ?Settings $settings = null;

    public function beforeAction($action): bool
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        /*if (Yii::$app->user->isGuest) {
            $url = Yii::$app->urlManagerPublic->createUrl(['/user/security/login']);
            Yii::$app->response->redirect($url);
            return false;
        }*/

        $this->user = User::findOne(1);
        //$this->user = Yii::$app->user->identity;

        if (!$this->user->checkRole(User::ROLE_ADMIN)) {
            throw new ForbiddenHttpException();
        }

        $this->settings = Settings::findOne(Settings::SETTINGS_ID);
        if (!$this->settings) {
            return false;
        }

        return true;
    }

    public function actionError(): string|false
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            $this->layout = 'error';
            if ($exception->statusCode == 404) {
                return $this->render('404', ['exception' => $exception]);
            }

            return $this->render('error', ['exception' => $exception]);
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function render($view, $params = []): string
    {
        return parent::render($view, array_merge($params, [
            'user' => $this->user
        ]));
    }

    /**
     * @inheritdoc
     */
    public function renderPartial($view, $params = []): string
    {
        return parent::renderPartial($view, array_merge($params, [
            'user' => $this->user
        ]));
    }
}
