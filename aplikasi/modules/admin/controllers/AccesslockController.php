<?php

namespace app\modules\admin\controllers;

use app\modelsDB\User;
use app\modelsDB\UserHasFakultas;
use app\modelsDB\UserHasDepartemen;
use app\modelsDB\UserHasProgramStudi;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class AccesslockController extends \yii\web\Controller
{
     /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $data = $this->getDataPengguna();
        return $this->render('index',[
            'data'=>$data,
        ]);
    }

    private function getDataPengguna()
    {
        $data = [];
        $datapengguna = User::find()->joinWith([
                                'userHasFakultas f'=> function($q){$q->orderBy('fakultas_id');},
                                'userHasDepartemens d'=> function($q){$q->orderBy('departemen_id');},
                                'userHasProgramStudis s'=> function($q){$q->orderBy('programStudi_id');}
                                    ])->where('accessRole_id IN ("4","5","6")')->orderBy(['accessRole_id'=>SORT_ASC])->all();

        foreach ($datapengguna as $p1) {
            $id = $p1['id'];
            $username = $p1['username'];
            switch ($p1['accessRole_id']){
                case 4 :
                    $p = UserHasFakultas::findOne($p1->id);
                    $data[$p['fakultas']['kode']]['name']= $username;
                    $data[$p['fakultas']['kode']]['id']= $id;
                    $data[$p['fakultas']['kode']]['aktif']= $p['lock_seleksi'];
                    break;
                case 5 :
                    $p = UserHasDepartemen::findOne($p1->id);
                    $fak = $p['departemen']['fakultas']['kode'];
                    if (isset($data[$fak]['jumdep']))
                        $data[$fak]['jumdep']++;
                    else
                        $data[$fak]['jumdep']=1;
                    $data[$fak][$p['departemen']['kode']]['name']= $username;
                    $data[$fak][$p['departemen']['kode']]['id']= $id;
                    $data[$fak][$p['departemen']['kode']]['aktif']= $p['lock_seleksi'];
                    break;
                case 6 :
                    $p = UserHasProgramStudi::findOne($p1->id);
                    $dep = $p['programStudi']['departemen']['kode'];
                    $fak = $p['programStudi']['departemen']['fakultas']['kode'];
                    if (isset($data[$fak]['jumprodi']))
                        $data[$fak]['jumprodi']++;
                    else
                        $data[$fak]['jumprodi']=1;
                    if (isset($data[$fak][$dep]['jumprodi']))
                        $data[$fak][$dep]['jumprodi']++;
                    else
                        $data[$fak][$dep]['jumprodi']=1;
                    $data[$fak][$dep][$p['programStudi']['kode']]['name']= $username;
                    $data[$fak][$dep][$p['programStudi']['kode']]['id']= $id;
                    $data[$fak][$dep][$p['programStudi']['kode']]['aktif']=$p['lock_seleksi'];
                    break;
                default:
                    break;
            }
        }
        return $data;
    }

    public function actionChangestate()
    {
        if (Yii::$app->request->isAjax){
            if(isset($_POST['state'])){
                $state = $_POST['state']['set'] == 'true' ? 1 : 0;
                $dtakses = $this->getSwitch($_POST['state']['id'],$state);
                
                if($dtakses)
                    echo json_encode('ok');
                else
                    echo json_encode('no');
            }
        }
    }

    public function actionSetlock()
    {
        if (Yii::$app->request->isAjax){
            if(isset($_POST['state'])){
                $state = $_POST['state'] == true ? 1 : 1;
                $user_id =  Yii::$app->user->identity->id;
                $dtakses = $this->getSwitch($user_id,$state);
                
                if($dtakses)
                    echo json_encode('ok');
                else
                    echo json_encode('no');
            }
        } 
    }

    private function getSwitch($user_id,$state)
    {
        $dtakses = User::findOne($user_id);
        switch ($dtakses['accessRole_id']){
                case 4 :
                    $dtakses = UserHasFakultas::findOne($user_id);
                    $connection = Yii::$app->db;
                    $dtakses = $connection ->createCommand()
                    ->update('user_has_fakultas', ['lock_seleksi' => $state], 'fakultas_id ='.$dtakses->fakultas_id)
                    ->execute();
                    break;
                case 5 :
                    $dtakses = UserHasDepartemen::findOne($user_id);
                    $connection = Yii::$app->db;
                    $dtakses = $connection ->createCommand()
                    ->update('user_has_departemen', ['lock_seleksi' => $state], 'departemen_id ='.$dtakses->departemen_id)
                    ->execute();
                    break;
                case 6 :
                    $dtakses = UserHasProgramStudi::findOne($user_id);
                    $connection = Yii::$app->db;
                    $dtakses = $connection ->createCommand()
                    ->update('user_has_programStudi', ['lock_seleksi' => $state], 'programStudi_id ='.$dtakses->programStudi_id)
                    ->execute();
                    break;
            }
            if($dtakses)
                   return true;
                else
                   return false;

            
    }

}
