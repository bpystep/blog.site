<?php

namespace common\modules\user\queries;

use common\modules\user\models\UserProfile;
use yii\db\ActiveQuery;

/**
 * @method UserProfile|null one($db = null)
 * @method UserProfile[]    all($db = null)
 */
class UserProfileQuery extends ActiveQuery
{
    public function sort(): self
    {
        return $this->orderBy([
            'user_profile.first_name' => SORT_ASC,
            'user_profile.last_name'  => SORT_ASC
        ]);
    }
}
