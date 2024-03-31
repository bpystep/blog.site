<?php

namespace common\modules\redaction\searches;

use common\modules\redaction\models\Category;
use yii\data\ActiveDataProvider;

class CategorySearch extends Category
{
    public function rules(): array
    {
        return [
            [['category_id'], 'integer'],
            [['title'], 'string'],
            [['title'], 'trim']
        ];
    }

    public function search(array $params = []): ActiveDataProvider
    {
        $query = Category::find()->sort();
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'category.category_id' => $this->category_id
        ])->andFilterWhere(['and',
            ['like', 'category.title', $this->title]
        ])->groupBy('category.category_id');

        return $dataProvider;
    }
}
