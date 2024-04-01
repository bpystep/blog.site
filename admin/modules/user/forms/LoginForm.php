<?php

namespace admin\modules\user\forms;

use common\constants\RegExp;
use common\modules\user\helpers\Password;
use common\modules\user\models\User;
use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public ?string $login = null;

    public ?string $password = null;

    public bool $rememberMe = false;

    protected ?User $user = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            [['login'], 'match', 'pattern' => RegExp::USERNAME, 'message' => Yii::t('user', 'Логин может содержать только английские символы и цифры')],
            [['login'], 'string', 'min' => 3, 'max' => 255],
            [['login'], 'trim'],
            [['password'], function($attribute) {
                if ($this->user === null || empty($this->user->password_hash) || !Password::validate($this->password, $this->user->password_hash)) {
                    $this->addError($attribute, Yii::t('user', 'Неправильный логин или пароль'));
                }
            }],
            [['rememberMe'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'LoginForm';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login'      => Yii::t('user', 'Логин или Email'),
            'password'   => Yii::t('user', 'Пароль'),
            'rememberMe' => Yii::t('user', 'Запомнить меня')
        ];
    }

    /**
     * @return boolean
     */
    public function login()
    {
        if ($this->validate()) {
            $module = Yii::$app->getModule('user');
            return Yii::$app->getUser()->login($this->user, $this->rememberMe ? $module->rememberFor : 0);
        }

        return false;
    }

    /**
     * @return boolean
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->user = User::find()->findUserByNicknameOrEmail($this->login)->one();

            return true;
        }

        return false;
    }
}
