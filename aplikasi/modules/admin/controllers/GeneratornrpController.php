<?php

namespace app\modules\admin\controllers;

use app\models\NrpGenerator;
use app\modules\admin\models\GenNrp;

class GeneratornrpController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new GenNrp();
        $gen = new NrpGenerator();
        $gen->gen();
        die;
        // return $this->render('index');
    }
    
    public function generateNrp()
    {
        
    }

}
