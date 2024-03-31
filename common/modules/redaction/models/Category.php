<?php

namespace common\modules\redaction\models;

use common\modules\redaction\queries\CategoryQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Database fields:
 * @property integer $category_id
 * @property string  $title
 * @property integer $rank
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 */
class Category extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'category';
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
            [['title'], 'trim'],
            [['rank'], 'integer']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'category_id' => Yii::t('app', 'ID'),
            'title'       => Yii::t('redaction', 'Название'),
            'rank'        => Yii::t('redaction', 'Ранг'),
            'created_by'  => Yii::t('app', 'Автор записи'),
            'updated_by'  => Yii::t('app', 'Автор изменений'),
            'created_at'  => Yii::t('app', 'Дата и время создания'),
            'updated_at'  => Yii::t('app', 'Дата и время обновления')
        ];
    }

    public static function find(): CategoryQuery
    {
        return new CategoryQuery(get_called_class());
    }
}
