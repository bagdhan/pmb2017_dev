<?php

namespace app\modules\verifikasi;

use Yii;
/**
 * Verifikasi module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\verifikasi\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (Yii::$app->user->isGuest){
            if (!Yii::$app->request->isAjax)
                Yii::$app->user->returnUrl = Yii::$app->request->url;
            Yii::$app->response->redirect(['/site/login']);
//            \yii\web\Controller::redirect(\Yii::$app->urlManager->createUrl("site/login"));
        }
    }
}
