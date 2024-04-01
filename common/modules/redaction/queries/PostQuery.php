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
    public function exclude(array|int $ids): self
    {
        return $this->andWhere(['not', ['post.post_id' => (array)$ids]]);
    }

    public function sort(): self
    {
        return $this->orderBy([
            'post.published_dt' => SORT_DESC
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

    public function onMain($is = true): self
    {
        return $this->andWhere(['post.on_main' => $is]);
    }

    public function inSlider($is = true): self
    {
        return $this->onMain()->andWhere(['post.in_slider' => $is]);
    }
}
