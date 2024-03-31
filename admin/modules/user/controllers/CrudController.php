<?php

namespace admin\modules\user\controllers;

use common\modules\user\models\User;
use common\traits\AjaxValidationTrait;
use Yii;
use yii\web\Response;

class CrudController extends DefaultController
{
    use AjaxValidationTrait;

    /**
     * @throws \yii\base\ExitException
     */
    public function actionUpdate(): string|Response
    {
        /* @var User $user */
        $user = $this->user;
        $profile = $user->profile;
        $user->setScenario('update');
        $this->performAjaxValidation($user);
        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            if ($user->save() && $profile->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Пользователь изменен'));
                return $this->refresh();
            } else {
                Yii::$app->getSession()->setFlash('danger', Yii::t('user', 'Произошла ошибка при изменении пользователя'));
            }
        }
        $roles = User::getAvailableRoles();

        return $this->render('update', [
            'user'   => $user,
            'module' => $this->module,
            'roles'  => $roles
        ]);
    }
}
