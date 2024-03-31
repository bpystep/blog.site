<?php

namespace common\modules\redaction\queries;

use common\modules\redaction\models\Category;
use yii\db\ActiveQuery;

/**
 * @method Category|null one($db = null)
 * @method Category[]    all($db = null)
 */
class TagQuery extends ActiveQuery
{
    public function sort(): self
    {
        return $this->orderBy([
            'tag.title' => SORT_ASC
        ]);
    }
}
