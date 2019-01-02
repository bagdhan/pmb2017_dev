<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Fakultas;
use app\usermanager\models\AccessRole;


class RekapController extends Controller
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
                        'actions' => ['index', 'view', 'update', 'create', 'delete'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return AccessRole::getAccess($action, $rule);
                        },
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    public function actionIndex($tahap=1, $tahun=0)
    {
        if (Yii::$app->user->isGuest){
            Yii::$app->user->returnUrl = Yii::$app->request->url;
            \yii\web\Controller::redirect(\Yii::$app->urlManager->createUrl("site/login"));
        }

        $fakultas = Fakultas::getFakultas();
        $tahun = ($tahun==0)? date('Y') : $tahun;
        $view = ($tahap==1)? $view = 'tahap1/index' : $view = 'tahap2\index';
        return $this->render($view, [
            'fakultas' => $fakultas,
            'tahun'=>$tahun,
            
        ]);
    }

    // public function actionRekap()
    // {
        // if (Yii::$app->user->isGuest){
        //     Yii::$app->user->returnUrl = Yii::$app->request->url;
        //     \yii\web\Controller::redirect(\Yii::$app->urlManager->createUrl("site/login"));
        // }
        // $fakultas = Pendaftaran::getFakultas();
        
        // return $this->render('chart2');
    // }

}
