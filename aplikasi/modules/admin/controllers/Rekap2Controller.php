<?php

namespace app\modules\pendaftar\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\ModelData;
use app\models\SecurityFilter;


class Rekap2Controller extends Controller
{
	public function behaviors()
    {
        return [
           
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return SecurityFilter::accessto($action);
                        },
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest){
            Yii::$app->user->returnUrl = Yii::$app->request->url;
            \yii\web\Controller::redirect(\Yii::$app->urlManager->createUrl("site/login"));
        }
        $fakultas = ModelData::getFakultas();
        
        return $this->render('index', [
            'fakultas' => $fakultas,
            
        ]);
    }

}
