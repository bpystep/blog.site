<?php

namespace common\modules\redaction\models;

use common\behaviors\TaggableBehavior;
use common\components\upload\UploadImageBehavior;
use common\helpers\ImageHelper;
use common\modules\redaction\queries\CategoryQuery;
use common\modules\redaction\queries\PostQuery;
use common\modules\redaction\queries\TagQuery;
use common\modules\user\models\User;
use common\modules\user\queries\UserQuery;
use common\traits\PublishStatusTrait;
use voskobovich\linker\LinkerBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Database fields:
 * @property integer $post_id
 * @property string  $title
 * @property string  $lead
 * @property string  $text
 * @property string  $image
 * @property string  $category_id
 * @property integer $is_public
 * @property string  $published_dt
 * @property string  $on_main
 * @property string  $in_slider
 * @property integer $is_temp
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * Defined relations:
 * @property User     $creator
 * @property Category $category
 * @property Tag[]    $tags
 *
 * Defined properties:
 * @property array $tagValues
 *
 * Defined methods:
 * @method getImageUrl($attribute, $thumbName = 'thumb')
 * @method attachImage($attribute, $imagePath)
 */
class Post extends ActiveRecord
{
    use PublishStatusTrait;

    public ?string $image_b64 = null;

    function getPublicAttribute(): string
    {
       return 'is_public';
    }

    function getPublishDtAttribute(): string
    {
        return 'published_dt';
    }

    public static function tableName(): string
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL
        ];
    }

    public function behaviors(): array
    {
        return [
            BlameableBehavior::class,
            TimestampBehavior::class,
            [
                'class'       => UploadImageBehavior::class,
                'attribute'   => 'image',
                'scenarios'   => [self::SCENARIO_DEFAULT],
                'placeholder' => '@common/modules/redaction/assets/images/post/image_placeholder.svg',
                'path'        => 'news/{post_id}',
                'url'         => 'news/{post_id}',
                'thumbs'      => [
                    'thumb'   => ['width' => 1920],
                    '870x490' => ['width' => 870, 'height' => 490], //1.775
                ]
            ],
            [
                'class'     => LinkerBehavior::class,
                'relations' => ['tagValues' => 'tags']
            ]
        ];
    }

    public function rules(): array
    {
        return [
            //required rules
            [['title', 'lead'], 'required'],
            //title rules
            [['title', 'lead', 'text'], 'string'],
            [['title', 'lead'], 'trim'],
            //image rules
            [['image'], 'image', 'extensions' => ImageHelper::AVAILABLE_EXTENSIONS],
            [['image_b64'], 'safe'],
            //category_id rules
            [['category_id'], 'integer'],
            //is_public rules
            [['is_public'], 'integer'],
            [['is_public'], 'default', 'value' => $this->getDefaultPublishStatus()],
            [['is_public'], 'filter', 'filter' => fn($value) => $this->getFilterRule($value)],
            //published_dt rules
            ['published_dt', 'date', 'timestampAttribute' => 'published_dt', 'timestampAttributeFormat' => 'php:Y-m-d H:i:s', 'format' => 'php:d.m.Y, H:i'],
            ['published_dt', 'filter', 'filter' => fn() => $this->prepareTimezone(true)],
            //on_main and in_slider rules
            [['on_main', 'in_slider'], 'boolean'],
            [['on_main', 'in_slider'], 'default', 'value' => false],
            //is_temp rules
            [['is_temp'], 'boolean'],
            [['is_temp'], 'default', 'value' => true],
            //tagValues rules
            [['tagValues'], 'safe']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'post_id'      => Yii::t('app', 'ID'),
            'title'        => Yii::t('redaction', 'Название'),
            'lead'         => Yii::t('redaction', 'Анонс'),
            'text'         => Yii::t('redaction', 'Текст'),
            'image'        => Yii::t('redaction', 'Изображение'),
            'category_id'  => Yii::t('redaction', 'Категория'),
            'is_public'    => Yii::t('redaction', 'Опубликована'),
            'published_dt' => Yii::t('redaction', 'Дата публикации'),
            'on_main'      => Yii::t('redaction', 'На главной'),
            'in_slider'    => Yii::t('redaction', 'В слайдере'),
            'is_temp'      => Yii::t('redaction', 'Временная'),
            'created_by'   => Yii::t('app', 'Автор записи'),
            'updated_by'   => Yii::t('app', 'Автор изменений'),
            'created_at'   => Yii::t('app', 'Дата и время создания'),
            'updated_at'   => Yii::t('app', 'Дата и время обновления'),
            'tagValues'    => Yii::t('app', 'Теги')
        ];
    }

    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if (!$insert) {
                $this->is_temp = false;
            }

            return true;
        }

        return false;
    }

    public static function find(): PostQuery
    {
        return new PostQuery(get_called_class());
    }

    public function getCreator(): UserQuery
    {
        return $this->hasOne(User::class, ['user_id' => 'created_by']);
    }

    public function getCategory(): CategoryQuery
    {
        return $this->hasOne(Category::class, ['category_id' => 'category_id']);
    }

    public function getTags(): TagQuery
    {
        return $this->hasMany(Tag::class, ['tag_id' => 'tag_id'])
            ->viaTable('post_tag_assn', ['post_id' => 'post_id']);
    }
}
