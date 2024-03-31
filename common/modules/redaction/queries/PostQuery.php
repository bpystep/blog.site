<?php

namespace common\modules\redaction\queries;

use common\constants\PublishStatuses;
use common\modules\redaction\models\Post;
use yii\db\ActiveQuery;

/**
 * @method Post|null one($db = null)
 * @method Post[]    all($db = null)
 */
class PostQuery extends ActiveQuery
{
    public function sort(): self
    {
        return $this->orderBy([
            'post.published_dt' => SORT_ASC
        ]);
    }

    public function published(): self
    {
        return $this->active()->andWhere(['and',
            ['post.is_public' => PublishStatuses::STATUS_PUBLIC],
            ['<=', 'post.published_dt', date('Y-m-d H:i')]
        ]);
    }

    public function active($is = true): self
    {
        return $this->andWhere(['post.is_temp' => !$is]);
    }
}
