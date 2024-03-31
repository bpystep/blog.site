<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Database fields:
 * @property integer $id
 *
 * Defined relations:
 * @property SettingsSocial[] $socials
 */
class Settings extends ActiveRecord
{
    const SETTINGS_ID = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'Идентификатор')
        ];
    }

    public function getSocials()
    {
        return $this->hasMany(SettingsSocial::class, ['settings_id' => 'id'])->orderBy(['isnull(`rank`)' => SORT_ASC, 'rank' => SORT_ASC]);
    }
}
