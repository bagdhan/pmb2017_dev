<?php

namespace app\modules\verifikasi\controllers;

use Yii;
use app\modules\verifikasi\models\Tableverifikasi;
use app\modules\verifikasi\models\SearchTableverifikasi;
use app\modules\verifikasi\models\Verifikasi;
use app\modules\verifikasi\models\AbsenForm;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MejaController implements the CRUD actions for Tableverifikasi model.
 */
class AntrianController extends Controller
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
        if (Yii::$app->user->isGuest){
            Yii::$app->user->returnUrl = Yii::$app->request->url;
            \yii\web\Controller::redirect(\Yii::$app->urlManager->createUrl("site/login"));
        }
        

        if(Yii::$app->request->queryParams){
            $post = Yii::$app->request->queryParams;
            
            $noPendaftaran = $post['n'];
            $tujuan = 'm1';
            $nmeja = Tableverifikasi::findOne(['name'=>'M1']);

            $daftarVerifikasi = Verifikasi::findOne(['noPendaftaran' => $noPendaftaran,'idTableVerifikasi'=>1]);
            if($daftarVerifikasi){
                $daftarVerifikasi->idTableVerifikasi = empty($nmeja)? 0 : $nmeja->id;
                $log = Json::decode($daftarVerifikasi->log);

                $temp['dateMasuk'] = date('Y-m-d H:i:s');
                $temp['posisi'] = $tujuan;

                $log[] = $temp;

                $daftarVerifikasi->log = Json::encode($log);
                if($daftarVerifikasi->save()){
                    $msg = 'Mahasiswa berhasil masuk M1';
                    $typemsg = 'success';
                }else{
                    $msg = 'Mahasiswa gagal masuk M1';
                    $typemsg = 'error';
                }
            }else{
                    $msg = 'Mahasiswa sudah berada di M1';
                    $typemsg = 'error';
            }

            $data['msg'] = $msg;
            $data['typemsg'] = $typemsg;       
        
        }

        $data['data'] = Verifikasi::getAntrian('and verif.idTableVerifikasi =2 and verif.tahap =6'); // diubah tadinya 4 jadi 6
        
        return $this->render('index', $data);
    }

     public function actionJumlahantrian()
    {
        $data = Verifikasi::getAntrian('and verif.idTableVerifikasi =2 and verif.tahap =6'); // diubah tadinya 4 jadi 6
        return sizeof($data);
    }

    

}
