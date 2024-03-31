<?php

namespace common\modules\redaction\models;

use common\modules\redaction\queries\PostQuery;
use common\modules\redaction\queries\TagQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Database fields:
 * @property integer $tag_id
 * @property string  $title
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * Defined relations:
 * @property Post[] $posts
 *
 * Defined properties:
 * @property string $name
 */
class Tag extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'tag';
    }

    public function behaviors(): array
    {
        return [
            BlameableBehavior::class,
            TimestampBehavior::class
        ];
    }

    public function rules(): array
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['title'], 'trim']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'tag_id'     => Yii::t('app', 'ID'),
            'title'      => Yii::t('redaction', 'Название'),
            'created_by' => Yii::t('app', 'Автор записи'),
            'updated_by' => Yii::t('app', 'Автор изменений'),
            'created_at' => Yii::t('app', 'Дата и время создания'),
            'updated_at' => Yii::t('app', 'Дата и время обновления')
        ];
    }

    public static function find(): TagQuery
    {
        return new TagQuery(get_called_class());
    }

    public function getName(): string
    {
        return "#{$this->title}";
    }

    public function getPosts(): PostQuery
    {
        return $this->hasMany(Post::class, ['post_id' => 'post_id'])
            ->viaTable('post_tag_assn', ['tag_id' => 'tag_id']);
    }
}
