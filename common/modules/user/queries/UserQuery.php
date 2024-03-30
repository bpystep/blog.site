<?php

namespace common\modules\user\models\queries;

use common\modules\user\models\User;
use yii\db\ActiveQuery;

/**
 * @method User|null one($db = null)
 * @method User[]    all($db = null)
 */
class UserQuery extends ActiveQuery
{
    public function sort(): self
    {
        return $this->joinWith('profile')->orderBy([
            'user_profile.first_name' => SORT_ASC,
            'user_profile.last_name'  => SORT_ASC
        ]);
    }

    public function byText(string $text = null): self
    {
        return $this->joinWith('profile')->andFilterWhere(['or',
            ['like', 'user.username', $text],
            ['like', 'user.email', $text],
            ['like', 'CONCAT_WS(" ", user_profile.first_name, user_profile.last_name)', $text]
        ]);
    }

    public function byEmail(string $email): self
    {
        return $this->andWhere(['email' => $email]);
    }

    public function byUsername(string $username): self
    {
        return $this->andWhere(['username' => $username]);
    }

    public function findUserByNicknameOrEmail(string $usernameOrEmail): self
    {
        if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->byEmail($usernameOrEmail);
        }

        return $this->byUsername($usernameOrEmail);
    }
}
