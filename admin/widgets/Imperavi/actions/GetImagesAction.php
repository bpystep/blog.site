<?php
namespace admin\widgets\imperavi\actions;

use admin\widgets\imperavi\models\ImageFile;
use yii\base\InvalidConfigException;
use yii\web\Response;
use Yii;
use yii\base\Action;

class GetImagesAction extends Action
{
    /* @var string */
    public $module;

    /**
     * @inheritdoc
     */
    public function run() {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if (empty($this->module)) {
                throw new InvalidConfigException('The "module" property must be required.');
            }

            /* @var $lastImages ImageFile[] */
            $lastImages = ImageFile::find()
                ->byModule($this->module)
                ->all();

            $data = [];
            foreach ($lastImages as $image) {
                $data[] = [
                    'id'    => $image->file_id,
                    'thumb' => $image->getImageUrl('file', '270x180'),
                    'url'   => $image->getImageUrl('file', '1920'),
                ];
            }


            return $data;
        }
    }
}
