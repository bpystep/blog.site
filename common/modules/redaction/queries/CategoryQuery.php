<?php

namespace common\modules\redaction\queries;

use common\modules\redaction\models\Category;
use yii\db\ActiveQuery;

/**
 * @method Category|null one($db = null)
 * @method Category[]    all($db = null)
 */
class CategoryQuery extends ActiveQuery
{
    public function sort(): self
    {
        return $this->orderBy([
            'isnull(`category`.`rank`)' => SORT_ASC,
            'category.rank'             => SORT_ASC,
            'category.title'            => SORT_ASC
        ]);
    }
}
