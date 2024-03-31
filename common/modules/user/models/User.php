<?php

namespace common\modules\user\models;

use common\constants\RegExp;
use common\modules\user\helpers\Password;
use common\modules\user\queries\UserProfileQuery;
use common\modules\user\queries\UserQuery;
use yii\base\NotSupportedException;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\web\User as BaseUser;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Database fields:
 * @property integer $user_id
 * @property string  $username
 * @property string  $email
 * @property integer $role
 * @property string  $password_hash
 * @property string  $auth_key
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * Defined relations:
 * @property UserProfile $profile
 *
 * Database properties:
 * @property string  $roleLabel
 * @property boolean $isAdmin
 */
class User extends ActiveRecord implements IdentityInterface
{
    const ROLE_GUEST = 0;
    const ROLE_USER  = 1;
    const ROLE_ADMIN = 1024;

    const BEFORE_CREATE   = 'beforeCreate';
    const AFTER_CREATE    = 'afterCreate';
    const BEFORE_REGISTER = 'beforeRegister';
    const AFTER_REGISTER  = 'afterRegister';
    const BEFORE_LOGIN    = 'beforeLogin';
    const AFTER_LOGIN     = 'afterLogin';
    const BEFORE_LOGOUT   = 'beforeLogout';
    const AFTER_LOGOUT    = 'afterLogout';

    const SCENARIO_UPDATE = 'update';
    const SCENARIO_ROLES  = 'roles';

    public ?string $password = null;

    private UserProfile $_profile;

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        Yii::$app->user->on(BaseUser::EVENT_AFTER_LOGIN,   [$this, self::AFTER_LOGIN]);
        Yii::$app->user->on(BaseUser::EVENT_BEFORE_LOGOUT, [$this, self::BEFORE_LOGOUT]);

        parent::init();
    }

    public static function tableName(): string
    {
        return 'user';
    }

    public function behaviors(): array
    {
        return [
            BlameableBehavior::class,
            TimestampBehavior::class
        ];
    }

    public function scenarios(): array
    {
        return [
            self::SCENARIO_UPDATE => ['username', 'email', 'password'],
            self::SCENARIO_ROLES  => ['role']
        ];
    }

    public function rules(): array
    {
        return [
            //required rules
            [['username', 'email'], 'required'],
            [['password'], 'required', 'except' => self::SCENARIO_UPDATE],
            //username rules
            [['username'], 'string', 'min' => 3, 'max' => 255],
            [['username'], 'trim'],
            [['username'], 'unique', 'message' => Yii::t('user', 'Этот логин уже используется')],
            [['username'], 'match', 'pattern' => RegExp::USERNAME],
            //email rules
            [['email'], 'string', 'max' => 255],
            [['email'], 'trim'],
            [['email'], 'email'],
            [['email'], 'unique', 'message' => Yii::t('user', 'Этот E-mail уже используется')],
            //password rules
            [['password'], 'string', 'min' => 6],
            //role rules
            [['role'], 'integer'],
            [['role'], 'default', 'value' => self::ROLE_USER]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'user_id'    => Yii::t('app', 'ID'),
            'username'   => Yii::t('user', 'Логин'),
            'email'      => Yii::t('user', 'E-mail'),
            'role'       => Yii::t('user', 'Роль'),
            'password'   => Yii::t('user', 'Пароль'),
            'auth_key'   => Yii::t('user', 'Ключ авторизации'),
            'created_by' => Yii::t('app', 'Автор записи'),
            'updated_by' => Yii::t('app', 'Автор изменений'),
            'created_at' => Yii::t('app', 'Дата и время создания'),
            'updated_at' => Yii::t('app', 'Дата и время обновления')
        ];
    }

    public static function find(): UserQuery
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->setAttribute('auth_key', Yii::$app->security->generateRandomString());
        }

        if (!empty($this->password)) {
            $this->setAttribute('password_hash', Password::hash($this->password));
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            if (is_null($this->_profile)) {
                $this->_profile = Yii::createObject(UserProfile::class);
                $this->_profile->username = $this->username;
            }
            $this->_profile->link('user', $this);
        }
    }

    public function afterLogin(): bool
    {
        $this->trigger(self::AFTER_LOGIN);
        return true;
    }

    public function beforeLogout(): bool
    {
        $this->trigger(self::BEFORE_LOGOUT);
        return true;
    }

    public static function findIdentity($id): self
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null): void
    {
        throw new NotSupportedException('Method "' . __CLASS__ . '::' . __METHOD__ . '" is not implemented.');
    }

    public function getId(): int
    {
        return $this->getAttribute('user_id');
    }

    public function getAuthKey(): string
    {
        return $this->getAttribute('auth_key');
    }

    /**
     * @param string $authKey
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAttribute('auth_key') === $authKey;
    }

    public function setProfile(UserProfile $profile): void
    {
        $this->_profile = $profile;
    }

    public function getProfile(): UserProfileQuery
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'user_id']);
    }

    public function resetPassword(string $password): bool
    {
        $result = !!$this->updateAttributes(['password_hash' => Password::hash($password)]);
        $this->trigger(self::EVENT_AFTER_UPDATE);

        return $result;
    }

    public static function getUsernameFromEmail(string $email, string $recursionUsername = null): bool
    {
        if (empty($email)) {
            return false;
        }

        if (!$recursionUsername) {
            $recursionUsername = substr($email, 0, strpos($email, '@'));
        }

        if (self::find()->byUsername($recursionUsername)->one()) {
            return self::getUsernameFromEmail($email, $recursionUsername . rand(0, 10000));
        }

        return $recursionUsername;
    }

    public static function getRoleLabels(): array
    {
        return [
            self::ROLE_GUEST => Yii::t('app', 'Гость'),
            self::ROLE_USER  => Yii::t('app', 'Пользователь'),
            self::ROLE_ADMIN => Yii::t('app', 'Админ')
        ];
    }

    public function getRoleLabel(): string
    {
        $roleLabels = self::getRoleLabels();
        return $roleLabels[$this->role] ?? $roleLabels[self::ROLE_GUEST];
    }

    public function getRoles(): array
    {
        $values = [];
        $roles = static::getAvailableRoles();
        foreach ($roles as $role => $title) {
            if (in_array($role, array_keys(self::getRoleLabels())) && $this->checkRole($role)) {
                $values[$role] = $title;
            }
        }

        return $values;
    }

    public function getIsAdmin(): bool
    {
        return $this->username == 'admin' || $this->checkRole(self::ROLE_ADMIN);
    }

    public static function getAvailableRoles(): array
    {
        return array_filter(self::getRoleLabels(), fn(int $role) => $role != self::ROLE_GUEST, ARRAY_FILTER_USE_KEY);
    }

    public function setRole(int $role, bool $active = true): void
    {
        $active ? $this->addRole($role) : $this->removeRole($role);
    }

    public function addRole(int $role): void
    {
        $this->role = $this->role | $role;
    }

    public function removeRole(int $role): void
    {
        $this->role = $this->role & ~$role;
    }

    public function checkRole(int $role, bool $strict = false): bool
    {
        $result = $this->role & $role;
        if ($strict) {
            $result = $result == $role;
        }

        return !!$result;
    }
}
