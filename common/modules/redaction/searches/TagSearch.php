<?php

namespace common\modules\redaction\searches;

use common\modules\redaction\models\Tag;
use yii\data\ActiveDataProvider;

class TagSearch extends Tag
{
    public function rules(): array
    {
        return [
            [['tag_id'], 'integer'],
            [['title'], 'string']
        ];
    }

    public function search(array $params = []): ActiveDataProvider
    {
        $query = Tag::find()->sort();
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'tag.tag_id' => $this->tag_id
        ])->andFilterWhere(['and',
            ['like', 'tag.title', $this->title]
        ])->groupBy('tag.tag_id');

        return $dataProvider;
    }
}
