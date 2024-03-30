<?php

namespace common\modules\user\models;

use common\components\upload\UploadImageBehavior;
use common\helpers\DateHelper;
use common\helpers\ImageHelper;
use common\modules\user\models\queries\UserProfileQuery;
use common\modules\user\models\queries\UserQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Database fields:
 * @property integer $user_id
 * @property string  $first_name
 * @property string  $last_name
 * @property string  $birthday
 * @property string  $photo
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * Defined properties:
 * @property string  $fullName
 * @property integer $age
 *
 * Defined relations:
 * @property User $user
 *
 * Defined methods:
 * @method getImageUrl($attribute, $size = 'thumb')
 */
class UserProfile extends ActiveRecord
{
    const SCENARIO_UPDATE = 'update';

    public ?string $photo_b64 = null;

    public static function tableName(): string
    {
        return 'user_profile';
    }

    public function behaviors(): array
    {
        return [
            BlameableBehavior::class,
            TimestampBehavior::class,
            [
                'class'       => UploadImageBehavior::class,
                'attribute'   => 'photo',
                'scenarios'   => ['default'],
                'placeholder' => '@common/modules/user/assets/images/profile/photo_placeholder.png',
                'path'        => 'user/{user_id}/photo',
                'url'         => 'user/{user_id}/photo',
                'thumbs'      => [
                    'thumb'   => ['width' => 512],
                    '200x200' => ['width' => 200, 'height' => 200], //ratio: 1
                    '80x80'   => ['width' => 80,  'height' => 80],
                    '30x30'   => ['width' => 30,  'height' => 30]
                ]
            ]
        ];
    }

    public function scenarios(): array
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_UPDATE => ['first_name', 'last_name', 'photo', 'photo_b64', 'birthday']
        ]);
    }

    public function rules(): array
    {
        return [
            //required rules
            [['first_name', 'last_name'], 'required', 'on' => self::SCENARIO_UPDATE],
            //name rules
            [['first_name', 'last_name'], 'string', 'max' => 255],
            [['first_name', 'last_name'], 'trim'],
            //birthday rules
            [['birthday'], 'date', 'timestampAttribute' => 'birthday', 'timestampAttributeFormat' => 'php:Y-m-d', 'format' => 'php:d.m.Y'],
            //photo rules
            [['photo'], 'image', 'extensions' => ImageHelper::AVAILABLE_EXTENSIONS],
            [['photo_b64'], 'safe']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'user_id'    => Yii::t('user', 'Пользователь'),
            'first_name' => Yii::t('user', 'Имя'),
            'last_name'  => Yii::t('user', 'Фамилия'),
            'photo'      => Yii::t('user', 'Аватар'),
            'birthday'   => Yii::t('user', 'Дата рождения'),
            'created_by' => Yii::t('app', 'Автор записи'),
            'updated_by' => Yii::t('app', 'Автор изменений'),
            'created_at' => Yii::t('app', 'Дата и время создания'),
            'updated_at' => Yii::t('app', 'Дата и время обновления')
        ];
    }

    public static function find(): UserProfileQuery
    {
        return new UserProfileQuery(get_called_class());
    }

    public function getUser(): UserQuery
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }

    public function getFullName(bool $defaultText = true): ?string
    {
        if ($this->last_name || $this->first_name) {
            return implode(' ', [$this->first_name, $this->last_name]);
        }

        if ($defaultText) {
            return Yii::t('app', 'Неопознанный медведь?');
        }

        return null;
    }

    public function getAge(): ?int
    {
        return $this->birthday ? DateHelper::getAge($this->birthday) : null;
    }
}
