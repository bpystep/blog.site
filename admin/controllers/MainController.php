<?php

namespace admin\controllers;

use common\models\SettingsSocial;
use common\traits\AjaxValidationTrait;
use Yii;
use yii\base\Model;
use yii\web\Response;

class MainController extends DefaultController
{
    use AjaxValidationTrait;

    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * @throws yii\base\ExitException
     */
    public function actionSettings(): string|Response
    {
        $this->performAjaxValidation($this->settings);
        if ($this->settings->load(Yii::$app->request->post())) {
            if ($this->settings->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('admin', 'Настройки обновлены'));
                return $this->refresh();
            } else {
                Yii::$app->getSession()->setFlash('danger', Yii::t('admin', 'Не удалось обновить настройки'));
            }
        }

        return $this->render('settings', [
            'settings' => $this->settings
        ]);
    }

    /**
     * @throws \Throwable
     * @throws yii\base\ExitException
     * @throws yii\db\StaleObjectException
     */
    public function actionSocials(): string|Response
    {
        if (Yii::$app->request->post()) {
            $socials = [];
            if ($data = Yii::$app->request->post('SettingsSocial', [])) {
                foreach ($data as $item) {
                    if (!empty($item['social_id'])) {
                        $socials[] = SettingsSocial::findOne($item['social_id']);
                    } else {
                        $socials[] = new SettingsSocial();
                    }
                }
                $this->performAjaxMultiValidation($socials);
                Model::loadMultiple($socials, Yii::$app->request->post());
                $submittedIds = [];
                foreach ($socials as $social) {
                    $social->settings_id = $this->settings->id;
                    $social->save();
                    $submittedIds[] = $social->social_id;
                }
                foreach ($this->settings->socials as $social) {
                    if (!in_array($social->social_id, $submittedIds)) {
                        $social->delete();
                    }
                }
                Yii::$app->getSession()->setFlash('success', Yii::t('common', 'Настройки обновлены'));
                return $this->refresh();
            } else {
                SettingsSocial::deleteAll(['settings_id' => $this->settings->id]);
            }
        } else if (!empty($this->settings->socials)) {
            $socials = $this->settings->socials;
        }
        if (empty($socials)) {
            $socials[] = new SettingsSocial();
        }
        $socialLabels = SettingsSocial::$icoLabels;

        return $this->render('socials', [
            'settings'     => $this->settings,
            'socials'      => $socials,
            'socialLabels' => $socialLabels
        ]);
    }
}
