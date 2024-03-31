<?php
use yii\helpers\Html;

/* @var $this           yii\web\View */
/* @var $model          yii\db\ActiveRecord */
/* @var $attribute      string */
/* @var $name           string */
/* @var $ratio          float */
/* @var $previewWidth   integer */
/* @var $previewHeight  integer */
/* @var $label          string */
/* @var $indexAttribute integer */

$cropperId = 'cropImage' . ucfirst($model->formName()) . ucfirst($attribute);
$cropperTarget = 'crop-image-' . $model->formName() . '-' . $attribute;

$imgParams['height'] = $previewHeight ? $previewHeight . 'px' : 'auto';
$imgParams['width']  = $previewWidth  ? $previewWidth  . 'px' : 'auto';
?>
<div class="border js-image-crop" data-ratio="<?php echo $ratio; ?>" style="<?php echo 'width: ' . $imgParams['width']; ?>; <?php echo 'height: ' . $imgParams['height']; ?>;">
    <div class="cropper">
        <a href="#<?php echo $cropperId; ?>" class="add-tooltip js-image-view" title="<?php echo $label; ?>" data-bs-placement="bottom" data-bs-toggle="modal" style="display: inline-block;">
            <?php echo Html::img($model->getImageUrl($attribute), [
                'alt'   => $label,
                'style' => [
                    'max-width' => '100%',
                    'width'     => $imgParams['width'],
                    'height'    => $imgParams['height']
                ]
            ]); ?>
        </a>
    </div>
    <div class="modal fade js-image-modal" id="<?php echo $cropperId; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $cropperTarget; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="<?php echo $cropperTarget; ?>"><?php echo $label; ?></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo Yii::t('common', 'Закрыть'); ?>"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="hidden" class="js-image-src" name="<?php echo ($name ?: $model->formName()) . (isset($indexAttribute) ? "[$indexAttribute]" : '') . '[' . $attribute . ']'; ?>" value="<?php if (isset($model[$attribute])) echo $model[$attribute]; ?>">
                                <input type="hidden" class="js-image-data" name="<?php echo ($name ?: $model->formName()) . (isset($indexAttribute) ? "[$indexAttribute]" : '') . '[' . $attribute . '_b64]'; ?>">
                                <input type="file" class="form-control js-image-input" id="inputImage<?php echo ucfirst($attribute); ?>" name="<?php echo $model->formName() . '[' . $attribute . '_file]'; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="image-wrapper js-image-wrapper"></div>
                        </div>
                        <div class="col-md-3">
                            <div class="preview-lg">
                                <div class="image-preview js-image-preview"></div>
                            </div>
                            <div class="preview-md">
                                <div class="image-preview js-image-preview"></div>
                            </div>
                            <div class="preview-sm">
                                <div class="image-preview js-image-preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary waves-effect waves-light" data-bs-dismiss="modal"><?php echo Yii::t('common', 'Закрыть'); ?></button>
                    <button type="button" class="btn btn-primary waves-effect waves-light js-image-save"><?php echo Yii::t('common', 'Применить'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
