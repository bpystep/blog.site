<?php
namespace admin\widgets\Imperavi\models;

use yii\db\ActiveQuery;

class ImageFileQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function init() {
        $this->where(['type' => File::TYPE_IMAGE]);
        parent::init();
    }

    /**
     * @param $module string
     * @param $limit integer
     * @return $this
     */
    public function byModule($module, $limit = 10) {
        $this->andWhere(['module' => $module])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit($limit);

        return $this;
    }
}
