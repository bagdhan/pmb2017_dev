<?php

namespace app\modules\pendaftaran\controllers;

//use yii\web\Controller;
use app\components\Controller;
use Yii;
use app\usermanager\models\AccessRole;
use app\modelsDB\Sidang;
use app\modelsDB\PaketPendaftaran;
use app\models\Pendaftaran;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use yii\web\NotAcceptableHttpException;

/**
 * Default controller for the `pendaftaran` module
 */
class DefaultController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'only' => [ 'index', 'view', 'update', 'create', 'delete'],
                'rules' => [
                    // allow authenticated users
                    [
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

    public function upfistarray($input){
        $pca = explode(' ', $input);
        $g='';
        foreach ($pca as $p)
            $g[]= ucfirst(strtolower($p));
        return join(' ',$g);
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPengumuman()
    {
        $st = [ 4 => "BIASA",
                5 => "PERCOBAAN",
                6 => "Ditolak",
                0 => '',
            ];

        $tingkat = ["2"=> "Magister Sains",
                  "3"=> "Doktor"];

        

        $pendaftaran = Pendaftaran::find()->where(['orang_id' => Yii::$app->user->identity->orang_id])->one();
           
            

        $nopendaftaran = $pendaftaran->noPendaftaran;
		$negara = $pendaftaran->orang->negara_id;
        $sd = ($pendaftaran->orang->jenisKelamin == 1)? 'Sdr. ' : 'Sdri. ';
        $menunda = $pendaftaran->cek_menunda();

        
          $surat = $pendaftaran->paketPendaftaran->surats;
            if($menunda == 1){
                  $paket = PaketPendaftaran::find()->where(['tahun'=>date('Y'),'title'=>'Tahap 1','active'=>1])->one();
                  $surat = isset($paket)? $paket->surats : '';
                }

            $tanggalPengumuman=[]; $tanggalPengumuman[0]=$tanggalPengumuman[1]='';
            if($surat != ''){
                foreach($surat as $sur){
                    $statusPenerimaan                       = $sur->statusPenerimaan;
                    $tanggalPengumuman[$statusPenerimaan]   = $sur->tanggalPengumumanBuka;
                }
            }

            if(date('Y-m-d H:i:s') < $tanggalPengumuman[1]){
              setlocale(LC_ALL, 'IND');
              $tanggal = strftime('%d %B %Y %X',strtotime($tanggalPengumuman[1]));
              throw new NotAcceptableHttpException("Pengumuman Seleksi belum dapat dibuka");
            }
        
        $pilihan=$inisial=$mayor=$kodefakultas=[]; $pilihan[1]=$pilihan[2]=$inisial[1]=$inisial[2]=$mayor[1]=$mayor[2]=$kodefakultas[1]=$kodefakultas[2]=''; $i = 1; 
        $model = $pendaftaran;
        foreach($model['pendaftaranHasProgramStudis'] as $value){
          $pilihan[$i] = $value->programStudi->kode;
          $mayor[$i] = $value->programStudi->nama;
          $inisial[$i] = $value->programStudi->inisial;
          $strata = $value->programStudi->strata;
          $kodefakultas[$i] = substr($value->programStudi->kode,0,1);
          $i++;
      }

      $paket = $pendaftaran->paketPendaftaran->title;
      $paketPleno = (isset($program->prosesSidangs[0]->sidang->id) && $pendaftaran->verifikasiPMB == 1)? $program->prosesSidangs[0]->sidang->paketPendaftaran->title : '';
      
      $tahun = $pendaftaran->paketPendaftaran->tahun;
      if($menunda == 1){
          $tahun = date('Y');
        }
      $program = $pendaftaran->pendaftaranHasProgramStudis[0];
      $pleno = ((isset($program->prosesSidangs[0]->sidang->id) && $pendaftaran->verifikasiPMB == 1) || $menunda == 1)? $program->prosesSidangs[0]->sidang->paketPendaftaran->title : '';
      if($pleno==''){
        $pleno = isset($pendaftaran->verifikasiPMB)? $pendaftaran->verifikasiPMB : 0;
      }

      $nama = $sd.' '.$this->upfistarray($pendaftaran->orang->nama);
      $jk = $pendaftaran->orang->jenisKelamin;

      $idhasil=[]; $idhasil[1]=$idhasil[2]=0;
      $i = 1;
      $program = $pendaftaran->pendaftaranHasProgramStudis;
      foreach($program as $prog){
              $proses_sidang = $prog->prosesSidangs;
              foreach($proses_sidang as $pr => $proses){
                $sidang = Sidang::findOne($proses->sidang_id);
                if($sidang->jenisSidang_id == 2){
                 $idhasil[$i] = (isset($proses->hasilKeputusan->id))? $proses->hasilKeputusan->id : 0;

             }
         }
         $i++;
      }

         $hsl = $idhasil[1];

         $strata = $tingkat[$strata];
         $ps = $mayor[1].' ('.$inisial[1].')';
         $status = $st[$hsl];
         $kodefak = $kodefakultas[1];
         if($idhasil[1] == 6 && $pilihan[2] != '' && $pilihan[1] != $pilihan[2]){
                // $idhasil=0;
            $hsl = $idhasil[2];

            $ps = $mayor[2].' ('.$inisial[2].')';
            $status = $st[$hsl];
            $kodefak = $kodefakultas[2];
        }
        $berkas = $pendaftaran->verifberkas();
        $berkas = isset($berkas)? 1 : 0;
        $setuju = isset($pendaftaran->setujuSyarat)? 1 : 0;
        // print_r($pleno);die();
        return $this->render('pengumuman',['nama'=>$nama,'nopendaftaran'=>$nopendaftaran,'pleno'=>$pleno,'berkas'=>$berkas,'setuju'=>$setuju,'hasil'=>$hsl,'strata'=>$strata,'ps'=>$ps,'status'=>$status,'tahun'=>$tahun,'paket'=>$paket,'paketPleno'=>$paketPleno,'menunda'=>$menunda]);
    }
}
