<?php

namespace rabint;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{

    public $globalXssFilter = true;
    public $globalBadFileFilter = true;

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function bootstrap($app)
    {

        if (Yii::$app instanceof \yii\console\Application) {
            return;
        }

//        $REQUEST = Yii::$app->getRequest();
//        if (
//                !isset($REQUEST->isGet) OR ! isset($REQUEST->isPost) OR ! isset($REQUEST->isAjax) OR ! isset($REQUEST->isPjax) OR ! isset($REQUEST->isPut)
//        ) {
//            return;
//        }

        static $isRanThisFunction = false;
        if (!$isRanThisFunction) {
            $isRanThisFunction = true;

            /**
             * global xss filter
             */
            if (config('SECURITY.globalXssFilter', $this->globalXssFilter)) {
                /** get filter */
                Yii::$app->request->setQueryParams(
                    \rabint\helpers\security::arrayXssClean(
                        Yii::$app->request->getQueryParams()
                    )
                );
                /** post filter */
                Yii::$app->request->setBodyParams(
                    \rabint\helpers\security::arrayXssClean(
                        Yii::$app->request->getBodyParams()
                    )
                );
            }
            /**
             * global bad uploaded file filter
             */
            if (config('SECURITY.globalBadFileFilter', $this->globalBadFileFilter)) {
                if (!empty($_FILES)) {
                    $files = $_FILES;

                    foreach ($_FILES as $key => $files) {


                        if (!is_array($files['name'])) {
                            $files['name'] = [$files['name']];
                            $files['type'] = [$files['type']];
                            $files['tmp_name'] = [$files['tmp_name']];
                            $files['error'] = [$files['error']];
                            $files['size'] = [$files['size']];
                        }
                        $files = \rabint\helpers\collection::rotateArray($files);
                        foreach ($files as $i => $file) {
                            $file = (object)$file;
                            if ($file->error > 0 && empty($file->name)) {
                                continue;
                            }
                            if (!\rabint\helpers\security::checkAllowedUploadedFile($file)) {
                                throw new \yii\web\ForbiddenHttpException(\Yii::t('rabint', 'Security Exception'));
                                die();
                            }
                        }
                    }

                }
            }


//        \Yii::$app->on('afterAction', function ($event) {
//            \rabint\stats\stats::stat();
//        });
//        \yii\base\Event::on(\yii\web\Controller::className(), \yii\web\Controller::EVENT_AFTER_ACTION, function ($event) {
//            \rabint\stats\stats::stat();
//        });
//            \yii\base\Event::on(\yii\web\Response::className(), \yii\web\Response::EVENT_AFTER_SEND, function ($event) {
//                \rabint\stats\stats::stat();
//            });
//        \Yii::$app->view->on(\yii\web\View::EVENT_END_PAGE, function () {
//            \rabint\stats\stats::stat();
//        });
        }
    }

}
