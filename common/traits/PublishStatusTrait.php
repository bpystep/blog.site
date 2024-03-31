<?php
namespace common\traits;

use common\constants\PublishStatuses;
use common\helpers\DateHelper;
use DateTime;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * Defined properties:
 * @property string $isPublic
 * @property string $publishedDt
 */
trait PublishStatusTrait
{
    abstract function getPublicAttribute(): ?string;

    abstract function getPublishDtAttribute(): ?string;

    public static function getPublishStatusLabels(): array
    {
        return PublishStatuses::getAll();
    }

    public function getPublishStatusLabel(bool $html = false, bool $short = false): ?string
    {
        /* @var ActiveRecord $model */
        $model = $this;
        $publicAttribute = $model->getPublicAttribute();

        if ($publicAttribute) {
            $label = !$short ? PublishStatuses::getAll()[$this->{$publicAttribute}] : (
                $this->{$publicAttribute} > 0 ? Yii::t('app', 'Да') : Yii::t('app', 'Нет')
            );

            if ($html) {
                $class = match ($this->{$publicAttribute}) {
                    PublishStatuses::STATUS_PUBLIC  => 'text-success',
                    PublishStatuses::STATUS_WAITING => 'text-info',
                    PublishStatuses::STATUS_DRAFT   => 'text-danger',
                    default => ''
                };

                return Html::tag('span', $label, array_filter(['class' => $class]));
            }

            return $label;
        }

        return null;
    }

    public function getIsPublic(): bool
    {
        /* @var ActiveRecord $model */
        $model = $this;

        $publicAttribute = $model->getPublicAttribute();
        $publishDtAttribute = $model->getPublishDtAttribute();

        if (!$publicAttribute && !$publishDtAttribute) {
            return false;
        }

        $is = !$publicAttribute || $model->{$publicAttribute} == PublishStatuses::STATUS_PUBLIC;
        $isDt = $publishDtAttribute || strtotime($model->published_dt) <= time();

        return $is && $isDt;
    }

    public function getDefaultPublishStatus(): int
    {
        return PublishStatuses::STATUS_DRAFT;
    }

    public function getFilterRule(?int $value = null): int
    {
        if (!in_array($value, array_keys(PublishStatuses::getData()))) {
            return $this->getDefaultPublishStatus();
        }

        return $value;
    }

    public function prepareTimezone(bool $isSafe = false): DateTime|string|null
    {
        /* @var ActiveRecord $model */
        $model = $this;
        $publishDtAttribute = $model->getPublishDtAttribute();

        if (!$publishDtAttribute) {
            return null;
        }

        if ($isSafe) {
            return DateHelper::fromTimezone($this->{$publishDtAttribute}, Yii::$app->formatter->siteTimeZone, 'Y-m-d H:i:s');
        }

        return DateHelper::toTimezone($this->{$publishDtAttribute}, Yii::$app->formatter->siteTimeZone, 'Y-m-d H:i:s');
    }

    public function deprepareTimezone(): void
    {
        /* @var ActiveRecord $model */
        $model = $this;
        $publishDtAttribute = $model->getPublishDtAttribute();

        if (!$publishDtAttribute) {
            return;
        }

        $this->{$attribute} = DateHelper::fromTimezone($this->{$attribute}, Yii::$app->formatter->siteTimeZone, 'Y-m-d H:i:s');
    }

    public function getPublishedDt(): ?string
    {
        /* @var ActiveRecord $model */
        $model = $this;
        $publishDtAttribute = $model->getPublishDtAttribute();

        if (!$publishDtAttribute) {
            return null;
        }

        if (!$this->{$publishDtAttribute}) {
            $this->{$publishDtAttribute} = date('Y-m-d H:i');
        }

        return $this->prepareTimezone();
    }
}
