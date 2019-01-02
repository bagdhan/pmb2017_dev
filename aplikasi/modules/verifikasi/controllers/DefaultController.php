<?php

namespace app\modules\verifikasi\controllers;

use Yii;
use yii\web\Controller;
use app\modules\verifikasi\models\Verifikasi;
use yii\helpers\Json;

/**
 * Default controller for the `Verifikasi` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
    	 if (Yii::$app->user->isGuest){
            Yii::$app->user->returnUrl = Yii::$app->request->url;
            \yii\web\Controller::redirect(\Yii::$app->urlManager->createUrl("site/login"));
        }

   		$data['data'] = Verifikasi::getAllverif();

        return $this->render('index',$data);
    }
}
