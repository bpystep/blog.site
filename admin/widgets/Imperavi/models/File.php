<?php
namespace admin\widgets\Imperavi\models;

use Yii;
use common\components\upload\UploadBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "file".
 *
 * Database fields:
 * @property integer $file_id
 * @property string  $type
 * @property string  $file
 * @property string  $module
 * @property integer $item_id
 * @property string  $year
 * @property string  $month
 * @property integer  $created_at
 */
class File extends ActiveRecord
{
    const TYPE_FILE     = 'file';
    const TYPE_IMAGE    = 'image';
    const TYPE_DOCUMENT = 'document';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'file';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['type', 'file', 'module'], 'required'],
            [['type', 'module'], 'string', 'max' => 255],
            [['year'], 'string', 'max' => 4],
            [['month'], 'string', 'max' => 2],
            [['created_at'], 'integer'],
            'file' => [['file'], 'file'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                'class'     => UploadBehavior::class,
                'attribute' => 'file',
                'scenarios' => ['default'],
                'path'      => $this->getPath(),
                'url'       => $this->getPath()
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'file_id'    => Yii::t('*', 'Идентификатор'),
            'type'       => Yii::t('*', 'Тип файла'),
            'file'       => Yii::t('*', 'Путь к файлу'),
            'module'     => Yii::t('*', 'Модуль'),
            'item_id'    => Yii::t('*', 'ID сущности'),
            'year'       => Yii::t('*', 'Год загрузки'),
            'month'      => Yii::t('*', 'Месяц загрузки'),
            'created_at' => Yii::t('*', 'Дата загрузки')
        ];
    }

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->type = self::TYPE_FILE;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $time = time();
                $this->created_at = $time;
                $this->year = date('Y', $time);
                $this->month = date('m', $time);
            }

            return true;
        }

        return false;
    }

    protected function getPath() {
        return 'content/{module}/{type}/{year}/{month}';
    }
}
