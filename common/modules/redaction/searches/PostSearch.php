<?php

namespace common\modules\redaction\searches;

use common\modules\redaction\models\Post;
use yii\data\ActiveDataProvider;

class PostSearch extends Post
{
    public function rules(): array
    {
        return [
            [['post_id', 'category_id', 'is_public'], 'integer'],
            [['title'], 'string'],
            [['title'], 'trim'],
            ['published_dt', 'date', 'timestampAttribute' => 'published_dt', 'timestampAttributeFormat' => 'php:Y-m-d', 'format' => 'php:d.m.Y']
        ];
    }

    public function search(array $params = []): ActiveDataProvider
    {
        $query = Post::find()->active()->sort();
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'post.post_id'            => $this->post_id,
            'post.is_public'          => $this->is_public,
            'DATE(post.published_dt)' => $this->published_dt
        ])->andFilterWhere(['and',
            ['like', 'post.title', $this->title]
        ])->groupBy('post.post_id');

        return $dataProvider;
    }
}
