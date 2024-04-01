<?php

namespace admin\modules\user\controllers;

use admin\modules\user\forms\LoginForm;
use common\modules\user\models\User;
use common\controllers\DefaultController as DefaultCommonController;
use common\traits\AjaxValidationTrait;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;

class SecurityController extends DefaultCommonController
{
    use AjaxValidationTrait;

    public $layout = '/default';

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'actions' => ['login'], 'roles' => ['?']],
                    ['allow' => true, 'actions' => ['logout'], 'roles' => ['@']]
                ]
            ],
            'verbs'  => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'logout' => ['post']
                ]
            ]
        ];
    }

    public function actionLogin(): string|Response
    {
        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }

        /* @var $model LoginForm */
        $model = Yii::createObject(LoginForm::class);
        $this->performAjaxValidation($model);
        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
            /* @var $user User */
            $user = Yii::$app->user->identity;

            Yii::$app->session->setFlash('success', Yii::t('user', 'Добро пожаловать {0}', $user->profile->fullName));
            return $this->redirect(Yii::$app->urlManager->createUrl(['/main/index']));
        }

        return $this->render('login', [
            'model' => $model
        ]);
    }

    public function actionLogout(): Response
    {
        Yii::$app->getUser()->logout();
        return $this->goHome();
    }
}
