<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\modules\admin\models\Email;
use app\models\Pendaftaran;
use app\models\JenisBerkas;
use app\models\SyaratBerkas;
use app\models\Excel;
use app\modelsDB\PaketPendaftaran;
use app\models\DoAktif;
use app\modelsDB\ProgramStudi;
use app\modelsDB\PilihKerjasama;
use app\modelsDB\ManajemenJalurMasuk;
use app\modelsDB\PendaftaranHasProgramStudi;
use app\modelsDB\PaketPendaftaranHasManajemenJalurMasuk;
use app\modelsDB\Sidang;
use app\modules\pleno\models\ProsesSidang;
use app\usermanager\models\AccessRole;
use mpdf;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
/**
 * Verifikasi controller for the `admin` module
 */
class VerifikasiController extends Controller
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
                'only' => ['index', 'view', 'update', 'create', 'delete'],
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

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        
        // print_r(Yii::$app->user->identity->accessRole_id);die();
        if (Yii::$app->user->isGuest || (Yii::$app->user->identity->accessRole_id > '3' && Yii::$app->user->identity->accessRole_id <= '7')){
            return Yii::$app->response->redirect(['/site/login']);
        }

        

        $data = Pendaftaran::find()->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                                'paketPendaftaran a'=> function($q){$q->where(['a.tahun'=> isset($_GET['tahun'])?$_GET['tahun'] : date('Y')]);},
                                              ])->orderBy('noPendaftaran DESC')->all();
        return $this->render('index', [
            'data' => $data,
  
        ]);
    }

    public function actionUpdatedata()
    {
    

        $value       = $_POST['sm']['value'];
        $nopend      = $_POST['sm']['nopend'];
        $kode        = $_POST['sm']['kode'];
        if($kode == 'pindah'){
            if($value == 1){ //pindah ke pendaftaran tahap 1
               $title = 'Tahap 1';
                $jalurMasukId = 1;
            }elseif($value == 2){ //pindah ke pendaftaran tahap 2
                $title = 'Tahap 2';
                $jalurMasukId = 1;
            }elseif($value == 3){ // pindah ke kelas khusus
                $title = 'Kelas Khusus';
                $jalurMasukId = 2;
            }elseif($value = 4){ // pindah ke kelas by research
                $title = 'Kelas by Research';
                $jalurMasukId = 3;
            }
            
            $paket = PaketPendaftaran::find()->where(['title'=>$title,'active'=>1])->one();
            $paket_id = empty($paket) ? null : $paket->id;
            if($paket_id !=null){
                $listid = ArrayHelper::getColumn(PaketPendaftaranHasManajemenJalurMasuk::find()
                ->where(['paketPendaftaran_id' => $paket_id])
                ->asArray()
                ->all(),'manajemenJalurMasuk_id');
                $pendaftaranHasProgramStudi1 = PendaftaranHasProgramStudi::findOne(['pendaftaran_noPendaftaran' => $nopend, 'urutan' => 1]);
                $pendaftaranHasProgramStudi2 = PendaftaranHasProgramStudi::findOne(['pendaftaran_noPendaftaran' => $nopend, 'urutan' => 2]);
                $pilihan1 = empty($pendaftaranHasProgramStudi1)? null : $pendaftaranHasProgramStudi1->programStudi_id;
                $pilihan2 = empty($pendaftaranHasProgramStudi2)? null : $pendaftaranHasProgramStudi2->programStudi_id;
                $manajemen = ManajemenJalurMasuk::find()
                ->where(['programStudi_id' => $pilihan1,'jalurMasuk_id'=>$jalurMasukId]);
                if(!empty($listid)){
                    $manajemen = $manajemen->andWhere("id IN (" . join(',',$listid) . ")")
                    ->one();
                }else{
                    $manajemen = null;
                }
                
                $manajemenJalurMasukId1 = empty($manajemen) ? null : $manajemen->id;

                if($manajemenJalurMasukId1==null){ // jika manajemen jalur masuknya tidak ada maka hapus pilihan prodi
                    $pen = PendaftaranHasProgramStudi::find()->where(['pendaftaran_noPendaftaran'=>$nopend])->one();
                    $pendaftaranHasProgramStudi_id = isset($pen)? $pen->id : '';
                    if($pendaftaranHasProgramStudi_id != ''){
                        PilihKerjasama::deleteAll(['pendaftaran_has_programStudi_id'=>$pendaftaranHasProgramStudi_id]);
                    }
                    
                    PendaftaranHasProgramStudi::deleteAll(['pendaftaran_noPendaftaran'=>$nopend]);
                }

                if($pilihan2!=null){
                    $manajemen = ManajemenJalurMasuk::find()
                    ->where(['programStudi_id' => $pilihan2,'jalurMasuk_id'=>$jalurMasukId]);
                    if(!empty($listid)){
                        $manajemen = $manajemen->andWhere("id IN (" . join(',',$listid) . ")")
                        ->one();
                    }else{
                        $manajemen = null;
                    }
                
                    $manajemenJalurMasukId2 = empty($manajemen) ? null : $manajemen->id;
                    if($manajemenJalurMasukId2==null){ // jika manajemen jalur masuknya tidak ada maka hapus pilihan prodi
                        PendaftaranHasProgramStudi::deleteAll(['pendaftaran_noPendaftaran'=>$nopend,'programStudi_id'=>$pilihan2]);
                    }
                }

                $pendaftaran = Pendaftaran::findOne($nopend);
                $pendaftaran->paketPendaftaran_id = $paket_id;
                $pendaftaran->manajemenJalurMasuk_id = $manajemenJalurMasukId1;
                
                if($pendaftaran->save(false)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }elseif($kode=='lengkap'){
            $value = $value - 1;
            $data = Pendaftaran::findOne($nopend);
            $data->setujuSyarat = $value;
            if($data->save()){
                return true;
            }else{
                return false;
            }      
        }elseif($kode=='berkas'){
            $value = $value - 1;
            $data = SyaratBerkas::find()->where(['pendaftaran_noPendaftaran'=>$nopend])->andWhere('jenisBerkas_id = 1 or jenisBerkas_id = 13')->one();
            $data->status = $value;
            $pendaftaran = $data->pendaftaranNoPendaftaran;
            if($data->save()){
                if($value == 1){
                    if (!empty($pendaftaran))
                    Email::sendEmailLengkap($pendaftaran);
                }
                return true;
            }else{
                return false;
            }   
        }elseif($kode=='do'){
            $value = $value - 1;
            $data = Pendaftaran::findOne($nopend);
                $data->aktifdo = $value;
            if($data->save()){
                return true;
            }else{
                return false;
            }   
        }elseif($kode =='pleno'){
            if($value == 1){
                $data = Pendaftaran::findOne($nopend);
                $data->verifikasiPMB = null;
                $data->save();

                $proses_sidang = ProsesSidang::find()->where(['pendaftaran_has_programStudi_id'=>$data->prodike(1)->id])->one();
                if($proses_sidang){
                    $sidang = ProsesSidang::deleteAll(['pendaftaran_has_programStudi_id'=>$data->prodike(1)->id]);
                }else{
                    $sidang = true;
                }

                if($sidang){
                    return true;
                }else{
                    return false;
                }

            }elseif($value == 5){ //tidak memenuhi
               $data = Pendaftaran::findOne($nopend);
                $data->verifikasiPMB = 2;
                $data->save();
                
                $proses_sidang = ProsesSidang::find()->where(['pendaftaran_has_programStudi_id'=>$data->prodike(1)->id])->one();
                if($proses_sidang){
                    $sidang = ProsesSidang::deleteAll(['pendaftaran_has_programStudi_id'=>$data->prodike(1)->id]);
                }else{
                    $sidang = true;
                }

                if($sidang){
                    return true;
                }else{
                    return false;
                }
            }elseif($value == 2){ //pleno 1 tahap 1
               $title = 'Tahap 1';
                
            }elseif($value == 3){ //pleno 1 tahap 2
                $title = 'Tahap 2';
             
            }elseif($value == 4){
                $title = 'Kelas Khusus';
				
            }elseif($value = 6){
                $title = 'Kelas by Research';
            }
            
            $data = Pendaftaran::findOne($nopend);
            $data->verifikasiPMB = 1;
            $data->save(false);
           

            $paket = PaketPendaftaran::find()->where(['title'=>$title,'active'=>1])->one();
            $paket_id = $paket->id;
            
            $sidang = Sidang::find()->where(['jenisSidang_id'=>1,'kunci'=>0,'paketPendaftaran_id'=>$paket_id])->one();
            $sidang_id = $sidang->id;
            
            $pendaftaranHasProgramStudi_id = $data->prodike(1)->id;

            $proses_sidang = ProsesSidang::find()->where(['pendaftaran_has_programStudi_id'=>$pendaftaranHasProgramStudi_id])->one();
            if(empty($proses_sidang)){
                $proses_sidang = new ProsesSidang();
                $proses_sidang->pendaftaran_has_programStudi_id = $pendaftaranHasProgramStudi_id;
            }
                $proses_sidang->sidang_id = $sidang_id;

            if($proses_sidang->save(false)){
                return true;
            }else{
                return false;
            }

        }elseif($kode =='save_ipks1'){
            $arr = explode("_", $nopend);
            $data = Pendaftaran::find()->where(['noPendaftaran'=>$arr[0]])->one()->pendidikansebelumnya(2);
            $ipkasal = $data->ipkAsal;
            if($ipkasal){
                $data->ipk = $value;
            }else{
                $data->ipk = $value;
                $data->ipkAsal =$arr[1];
            }

            if($data->save()){
                return $data->ipkAsal;
            }else{
                return false;  
            } 
        }elseif($kode =='save_ipks2'){
            $arr = explode("_", $nopend);
            $data = Pendaftaran::find()->where(['noPendaftaran'=>$arr[0]])->one()->pendidikansebelumnya(3);
            $ipkasal = $data->ipkAsal;
            if($ipkasal){
                $data->ipk = $value;
            }else{
                $data->ipk = $value;
                $data->ipkAsal =$arr[1];
            }

            if($data->save()){
                return $data->ipkAsal;
            }else{
                return false;  
            }  
        }elseif($kode =='cetak_unggah'){
            $data = SyaratBerkas::find()->where(['pendaftaran_noPendaftaran'=>$nopend,'jenisBerkas_id'=>$value])->one();
            $data->verifikasi = 1;
            $data->dateVerifikasi = date('Y-m-d H:i:s');
            if($data->save()){
                return true;
            }else{
                return false;
            }
        }
        elseif($kode =='batal_cetak'){
            $data = SyaratBerkas::find()->where(['pendaftaran_noPendaftaran'=>$nopend,'jenisBerkas_id'=>$value])->one();
            $data->verifikasi = 0;
            $data->dateVerifikasi = date('Y-m-d H:i:s');
            if($data->save()){
                return true;
            }else{
                return false;
            }
        }elseif($kode =='save_akrs1'){
            $data = Pendaftaran::find()->where(['noPendaftaran'=>$nopend])->one()->pendidikansebelumnya(2);
            $data->akreditasi = $value; 
            if($data->save()){
                return true;
            }else{
                return false;
            }
        }elseif($kode =='save_akrs2'){
            $data = Pendaftaran::find()->where(['noPendaftaran'=>$nopend])->one()->pendidikansebelumnya(3);
            $data->akreditasi = $value; 
            if($data->save()){
                return true;
            }else{
                return false;
            }
        }

    }
    

    public function actionCetak($id)
    {

    $data = Pendaftaran::findOne($id);
    $document = Pendaftaran::findOne($id)->syaratBerkas;
    $mpdf=new mpdf('','A4');
    
        $mpdf->WriteHTML($this->renderPartial('_reportView',array('data'=>$data)));
        $mpdf->AddPage();
        $mpdf->WriteHTML($this->renderPartial('_reportCover',array('data'=>$data,'document'=>$document)));
        $mpdf->Output();


    }

    public function template($nopendaftaran)
    {
        $doc = array();
        $data2 = Pendaftaran::findOne($nopendaftaran)->syaratBerkas;
        foreach ($data2 as $d2) {
            if($d2['pendaftaran_noPendaftaran'] == $nopendaftaran){
                $docid = $d2['jenisBerkas_id'];
                $doc[$d2['pendaftaran_noPendaftaran']][$docid]['file']=$d2['file'];
                $doc[$d2['pendaftaran_noPendaftaran']][$docid]['cetak']=$d2['verifikasi'];
            }
        }
        $data = Pendaftaran::findOne($nopendaftaran);
        
        

        // $ipk1asl = ($data->ipksebelumnya('S2')!=null)  ? ' (IPK Asal : '.$data->ipksebelumnya('S2').')' : '' ;
        // $ipk2asl = ($data->ipksebelumnya('S3')!=null)  ? ' (IPK Asal : '.$data->ipksebelumnya('S3').')' : '' ;
        
        $ipk1asl = ($data->ipksebelumnya('2')['ipkasal']!=null)  ? ' (IPK Asal : '.$data->ipksebelumnya('2')['ipkasal'].')' : '' ;
        $ipk2asl = ($data->ipksebelumnya('3')['ipkasal']!=null)  ? ' (IPK Asal : '.$data->ipksebelumnya('3')['ipkasal'].')' : '' ;

        $ipk1 = $data->ipksebelumnya('2')['ipk'].$ipk1asl;
        $ipk2 = $data->ipksebelumnya('3')['ipk'].$ipk2asl;
        
        $ipk1class = $data->ipksebelumnya('2')['ipk'] < 2.5 ? "class='danger'" : "";
        $ipk2class = $data->ipksebelumnya('3')['ipk'] < 3.0 ? "class='danger'" : "";
        $list_kontak = $data->orang->kontaks;
        $phone = $hp = $email = $emailalternatif = '';
        foreach($list_kontak as $value){
            $jeniskontak = $value->jenisKontak_id;
            if( $jeniskontak== 1){ // email
                $email = $value->kontak;
            }elseif($jeniskontak == 2){ // hp
                $hp = $value->kontak;
            }elseif($jeniskontak == 3){ // tlp. rumah
                $phone = $value->kontak;
            }
        }
        $phone = ($phone!='') ? $phone.' / ' : '- / '; 
        $hp = ($hp!='') ? $hp : '-'; 
        $telp = $phone.$hp;

        $mail = ($email !='') ? $email : '';
        $emailalternatif = ($emailalternatif !='') ? ' / '.$emailalternatif : '';
        $email = $mail.$emailalternatif;

        // if($data->keterangan){ // penanda double degree
        //     $dd =" <tr style='margin:0; width:100%; border-bottom-style: dashed; border-width: thin;'><td>Double Degree</td><td>$data->keterangan</td></tr>";
        // }else{
            $dd ='';
        // }

        // identifikasi mahaiswa do dan aktif belum dianalisis lagi
        $flag_do = DoAktif::getDataDoAktif($data->orang->nama, $data->orang->tempatLahir, $data->orang->tanggalLahir,0); 
        $flag_aktif = DoAktif::getDataDoAktif($data->orang->nama, $data->orang->tempatLahir, $data->orang->tanggalLahir,1); 
        $countDo = count($flag_do); 
        $countAktif = count($flag_aktif); 

        if($countDo >= 1 || $countAktif >= 1){
            $fileup='';
            $listdoatf="";
            $no = 1; 
            foreach($flag_do as $data_do){

                $listdoatf .=" <tr style='margin:0; width:100%; border-bottom-style: dashed; border-width: thin;'>
                                <td width='20%'>Data DO $no</td><td>".$data_do['nim']." ".$data_do['nama']." ".$data_do['tempat_lahir'].", ".date("d-m-Y",strtotime($data_do['tanggal_lahir']))."</td>  
                                </tr>";
                $no++; 
            }

            $no = 1; 
            foreach($flag_aktif as $data_aktif){
                $listdoatf .= "<tr style='margin:0; width:100%; border-bottom-style: dashed; border-width: thin;'>
                                <td width='20%'>Data MhsAktif $no</td><td>".$data_aktif['nim']." ".$data_aktif['nama']." ".$data_aktif['tempat_lahir'].", ".date("d-m-Y",strtotime($data_aktif['tanggal_lahir']))." ".$data_aktif['strata']." ".$data_aktif['program_studi']."</td>  
                                </tr>";
                $no++;
            }
            $fileup.= "$listdoatf"; 
            $fileupaksi = '';
            if($data->aktifdo == '0'){
                $aksidoaktf = "<option value='1' selected>Tidak Terindikasi</option>
                               <option value='2' >Terindikasi</option>";
            }else{
                $aksidoaktf = "<option value='1' >Tidak Terindikasi</option>
                               <option value='2' selected>Terindikasi</option>";
            }
            $fileupaksi .="$aksidoaktf";
            $doaktf =" 
            <div class='col col-md-7'>
            <div class='panel panel-info'>
                <div class='panel-heading'>
                    <h3 class='panel-title'><a role='button' data-toggle='collapse' data-parent='#accordion' 
                        href='#DO".$nopendaftaran."' aria-expanded='true' aria-controls='collapseOne'> 
                     Indikasi Mahasiswa Aktif dan DO<i style='font-size:10pt;'> (Klik untuk selengkapnya)</i></a> </h3>
                </div>
                <div id='DO".$nopendaftaran."' class='panel-collapse collapse' role='tabpanel' aria-labelledby='headingOne'>
                    <div class='panel-body'>
                        <div class='row'>
                            <div class=' col-sm-8'>
                                <table style='margin:0; width:100%;'>
                                 $fileup
                                </table>
                            </div>
                            <div class='col-sm-4'>
                                <table style='margin:0; width:100%;'>
                                    <tr>
                                        <td>
                                            <select class='form-control' id='do_".$nopendaftaran."' name='".$nopendaftaran."' onchange='submit_do(this.value, this.name);'>
                                                $fileupaksi                           
                                            </select>
                                        </td>
                                        <td>
                                            <div style='display: none;' id='do_".$nopendaftaran."_save'> <span title='Save' style='color:green;' class='glyphicon glyphicon-saved' aria-hidden='true'></span><div>
                                        </td>
                                    </tr>
                                    
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ";
        }else{
            $doaktf ='';
        }
        $cek = $data->prodike(1);
        $jenjang = isset($cek) ? $cek->programStudi->strata : '2';
        if ($jenjang == '2'){
            $strata = "<option value='S2' selected>Magister</option>
                      <option value='S3' >Doktor</option>";
        }else{
            $strata = "<option value='S2' >Magister</option>
                      <option value='S3' selected>Doktor</option>";
        }

        if ($data->setujuSyarat == null){
             $forma ="<option value='1' selected>Belum Lengkap</option>
                      <option value='2' >Lengkap</option>";
        }else{
             $forma ="<option value='1' >Belum Lengkap</option>
                      <option value='2' selected>Lengkap</option>";
        }

        if ($data->verifberkas() == false){
              $berkas = "<option value='1' selected>Belum Lengkap</option>
                        <option value='2' >Lengkap</option>";
        }else{
              $berkas = "<option value='1' >Belum Lengkap</option>
                        <option value='2' selected>Lengkap</option>";
        }

        if ($data->verifikasiPMB == '0'|| !isset($data->verifikasiPMB)){
              $pleno = "<option value='1' selected>Belum Pilih</option>
                        <option value='2' >Tahap 1</option>
                        <option value='3' >Tahap 2</option>
                        <option value='4' >Kelas Khusus</option>
                        <option value='6' >Kelas by Research</option>
                        <option value='5' >Tidak Memenuhi</option>";
        }elseif($data->verifikasiPMB == '1' && $data->pendaftaranHasProgramStudis[0]->prosesSidangs[0]->sidang->paketPendaftaran->title == 'Tahap 1'){
              $pleno = "<option value='1' >Belum Pilih</option>
                        <option value='2' selected>Tahap 1</option>
                        <option value='3' >Tahap 2</option>
                        <option value='4' >Kelas Khusus</option>
                        <option value='6' >Kelas by Research</option>
                        <option value='5' >Tidak Memenuhi</option>";
        }elseif($data->verifikasiPMB == '1' && $data->pendaftaranHasProgramStudis[0]->prosesSidangs[0]->sidang->paketPendaftaran->title == 'Tahap 2'){
              $pleno = "<option value='1' >Belum Pilih</option>
                        <option value='2' >Tahap 1</option>
                        <option value='3' selected>Tahap 2</option>
                        <option value='4' >Kelas Khusus</option>
						<option value='6' >Kelas by Research</option>
                        <option value='5' >Tidak Memenuhi</option>";
        }elseif($data->verifikasiPMB == '1' && $data->pendaftaranHasProgramStudis[0]->prosesSidangs[0]->sidang->paketPendaftaran->title == 'Kelas Khusus'){
              $pleno = "<option value='1' >Belum Pilih</option>
                        <option value='2' >Tahap 1</option>
                        <option value='3' >Tahap 2</option>
                        <option value='4' selected>Kelas Khusus</option>
						<option value='6' >Kelas by Research</option>
                        <option value='5' >Tidak Memenuhi</option>";
        }elseif($data->verifikasiPMB == '1' && $data->pendaftaranHasProgramStudis[0]->prosesSidangs[0]->sidang->paketPendaftaran->title == 'Kelas by Research'){
              $pleno = "<option value='1' >Belum Pilih</option>
                        <option value='2' >Tahap 1</option>
                        <option value='3' >Tahap 2</option>
                        <option value='4' >Kelas Khusus</option>
						<option value='6' selected>Kelas by Research</option>
                        <option value='5' >Tidak Memenuhi</option>";
        }elseif($data->verifikasiPMB == '2'){
              $pleno = "<option value='1' >Belum Pilih</option>
                        <option value='2' >Tahap 1</option>
                        <option value='3' >Tahap 2</option>
                        <option value='4' >Kelas Khusus</option>
						<option value='6' >Kelas by Research</option>
                        <option value='5' selected>Tidak Memenuhi</option>";
        }

        // print_r($data->pendaftaranHasProgramStudis[0]->prosesSidangs[0]->sidang->paketPendaftaran->title);die();

        $paket = PaketPendaftaran::findOne($data->paketPendaftaran_id);

        $title = $paket->title;
        if ($title == 'Tahap 1'){
              $pindah = "<option value='1' selected>Tahap 1</option>
                        <option value='2' >Tahap 2</option>
                        <option value='3' >Kelas Khusus</option>
						<option value='4' >Kelas by Research</option>";
        }elseif($title == 'Tahap 2'){
              $pindah = "<option value='1' >Tahap 1</option>
                        <option value='2' selected>Tahap 2</option>
                        <option value='3' >Kelas Khusus</option>
						<option value='4' >Kelas by Research</option>";
        }elseif($title == 'Kelas Khusus'){
              $pindah = "<option value='1' >Tahap 1</option>
                        <option value='2' >Tahap 2</option>
                        <option value='3' selected>Kelas Khusus</option>
						<option value='4' >Kelas by Research</option>";
        }elseif($title == 'Kelas by Research'){
              $pindah = "<option value='1' >Tahap 1</option>
                        <option value='2' >Tahap 2</option>
                        <option value='3' >Kelas Khusus</option>
						<option value='4' selected>Kelas by Research</option>";
        }

        //aksi 

        $aksi = "
        <div class='col col-md-7'>
        <div class='panel panel-info'>
            <div class='panel-heading'>
                <h3 class='panel-title'><a role='button' data-toggle='collapse' data-parent='#accordion' 
                    href='#Aksi".$nopendaftaran."' aria-expanded='true' aria-controls='collapseOne'> 
                 Aksi <i style='font-size:10pt;'> (Klik untuk selengkapnya)</i></a></h3>
            </div>
            <div id='Aksi".$nopendaftaran."' class='panel-collapse collapse' role='tabpanel' aria-labelledby='headingOne'>
                <div class='panel-body'>
                    <div class='row'>
                        <div class='col-sm-12'>
                            <table style='margin:0; width:100%;'>
                                <tr>
                                    <td>
                                       Pernyataan Data Lengkap Formulir A
                                    </td>
                                    <td>
                                        <select class='form-control' id='lengkap_".$nopendaftaran."' name='".$nopendaftaran."' onchange='submit_lengkap(this.value, this.name);'>
                                            '$forma'                      
                                        </select>
                                    </td>
                                    <td>
                                        <div style='display: none;' id='lengkap_".$nopendaftaran."_save'> <span title='Save' style='color:green;' class='glyphicon glyphicon-saved' aria-hidden='true'></span><div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Berkas Unggah Lengkap
                                    </td>
                                    <td>
                                        <select class='form-control' id='berkas_".$nopendaftaran."' name='".$nopendaftaran."' onchange='submit_berkas(this.value, this.name);'>
                                            '$berkas'                             
                                            </select>
                                    </td>
                                    <td>
                                        <div style='display: none;' id='berkas_".$nopendaftaran."_save'> <span title='Save' style='color:green;' class='glyphicon glyphicon-saved' aria-hidden='true'></span><div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Pleno 1
                                    </td>
                                    <td>
                                        <select class='form-control' id='pleno_".$nopendaftaran."' name='".$nopendaftaran."' onchange='submit_pleno(this.value, this.name);'>
                                            '$pleno'                             
                                            </select>
                                    </td>
                                    <td>
                                        <div style='display: none;' id='pleno_".$nopendaftaran."_save'> <span title='Save' style='color:green;' class='glyphicon glyphicon-saved' aria-hidden='true'></span><div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Paket Pendaftaran
                                    </td>
                                    <td>
                                        <select class='form-control' id='pindah_".$nopendaftaran."' name='".$nopendaftaran."' onchange='submit_pindah(this.value, this.name);'>
                                            '$pindah'                             
                                            </select>
                                    </td>
                                    <td>
                                        <div style='display: none;' id='pindah_".$nopendaftaran."_save'> <span title='Save' style='color:green;' class='glyphicon glyphicon-saved' aria-hidden='true'></span><div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>";

        $tgllhir = date("d-m-Y",strtotime($data->orang->tanggalLahir));
        $dir = Yii::getAlias('@arsipdir'). '/' .$nopendaftaran ;
        $dirpen = '';
        if(is_dir($dir)){
            Yii::$app->assetManager->publish($dir);
            $dirpen = Yii::$app->assetManager->getPublishedUrl($dir) ."/";
        }
        $fileup='';
        $doctype = JenisBerkas::find()->where(['strata'=>$jenjang])->all();   

        if ($jenjang == '2'){
            foreach ($doctype as $d2) {
              
                $docid = $d2['id'];
                if(isset($doc[$nopendaftaran][$docid]) && $doc[$nopendaftaran][$docid]['cetak'] == '1') {
                        $info_color = 'style="color:green;"'; 
                        $display='style="color:red;"';
                    }else{ 
                        $info_color='';
                        $display ='style="display: none;"';
                    }
                $showfile ="<tr style='margin:0; width:100%; border-bottom-style: dashed; border-width: thin;'>
                                <td>
                                    ".$d2['nama']."
                                </td>
                                <td align='center'>";
                $showfile .= isset($doc[$nopendaftaran][$docid]) ? 
                        "<a href='$dirpen".$doc[$nopendaftaran][$docid]['file']."' target='_balnk' id='".$nopendaftaran."_".$docid."' name='".$docid."' onclick='cetak_unggah(this.id, this.name);' role='button'>
                        <span title='Lihat dan cetak' class='glyphicon glyphicon-search' aria-hidden='true' ".$info_color."></span></a> <a id='batal_".$nopendaftaran."_".$docid."' name='".$docid."' onclick='batal_cetak(this.id, this.name);' role='button' ".$display."><span title='Batal Cetak' class='glyphicon glyphicon-remove' aria-hidden='true' style='color:red;''></span></a></td></tr>" : "<span title='Belum Unggah Berkas' class='glyphicon glyphicon-remove' aria-hidden='true'></span></td></tr>"; 

                        $fileup .= "$showfile";          
                
             
            }
            $jipk = 'IPK S1';
            $ipk = $ipk1;
            $editipk = "<input id='edit_".$nopendaftaran."' style='display: none;' type='text' name='".$nopendaftaran."_".$data->ipksebelumnya('2')['ipk']."' class='form-control' value='".$data->ipksebelumnya('2')['ipk']."' onchange='save_ipks1(this.value, this.name);' />";
            $pendidikan = $data->pendidikansebelumnya('2'); 
            $akr = isset($pendidikan) ? $pendidikan->akreditasi : '';
            $ptasal = isset($pendidikan) ? $pendidikan->institusi->nama." (Prodi : ". $pendidikan->programStudi.")" : "";

                
        }
        elseif($jenjang == '3'){
            foreach ($doctype as $d2) {
                
                $docid = $d2['id'];
                if(isset($doc[$nopendaftaran][$docid]) && $doc[$nopendaftaran][$docid]['cetak'] == '1') {
                        $info_color = 'style="color:green;"'; 
                        $display='style="color:red;"';
                    }else{ 
                        $info_color='';
                        $display ='style="display: none;"';
                    }
                $showfile ="<tr style='margin:0; width:100%; border-bottom-style: dashed; border-width: thin;'>
                                <td>
                                    ".$d2['nama']."
                                </td>
                                <td align='center'>";
                $showfile .= isset($doc[$nopendaftaran][$docid]) ? 
                        "<a href='$dirpen".$doc[$nopendaftaran][$docid]['file']."' target='_balnk' id='".$nopendaftaran."_".$docid."' name='".$docid."' onclick='cetak_unggah(this.id, this.name);' role='button'>
                        <span title='Lihat dan cetak' class='glyphicon glyphicon-search' aria-hidden='true' ".$info_color."></span></a> <a id='batal_".$nopendaftaran."_".$docid."' name='".$docid."' onclick='batal_cetak(this.id, this.name);' role='button' ".$display."><span title='Batal Cetak' class='glyphicon glyphicon-remove' aria-hidden='true' style='color:red;''></span></a></td></tr>" : "<span title='Belum Unggah Berkas' class='glyphicon glyphicon-remove' aria-hidden='true'></span></td></tr>"; 
                        $fileup .= "$showfile";          
                
          
            }
            $jipk = 'IPK S2';
            $ipk = $ipk2;
            $pendidikan = $data->pendidikansebelumnya('3'); 
            $akr = isset($pendidikan) ? $pendidikan->akreditasi : '';
            $ptasal = isset($pendidikan) ? $pendidikan->institusi->nama." (Prodi : ". $pendidikan->programStudi.")" : "";
            $editipk = "<input id='edit_".$nopendaftaran."' style='display: none;' type='text' name='".$nopendaftaran."_".$data->ipksebelumnya('3')['ipk']."' class='form-control' value='".$data->ipksebelumnya('3')['ipk']."' onchange='save_ipks2(this.value, this.name);' />";
        }
        
        $tp = "
        <div class='col col-md-4'>
        <div class='panel panel-info'>
            <div class='panel-heading'>
                <h3 class='panel-title'> Berkas Unggah Pelamar </h3>
            </div>
            <div class='panel-body'>
                <div class='row'>
                    <div class=' col-sm-12'>
                        <table  style='margin:0; width:100%;'>
                        $fileup
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class='col col-md-7'>
        <div class='panel panel-info'>
            <div class='panel-heading'>
                <h3 class='panel-title'> Informasi Pelamar </h3>
            </div>
            
                <div class='panel-body'>
                    <div class='row'>
                        <div class=' col-sm-12'>
                            <table style='margin:0; width:100%;''>
                                <tbody>
                                    <tr style='margin:0; width:100%; border-bottom-style: dashed; border-width: thin;'><td>Username</td><td>".$data->orang->user->username."</td></tr>
                                    <tr style='margin:0; width:100%; border-bottom-style: dashed; border-width: thin;'><td>$jipk</td>
                                        <td><span id='ipk_".$nopendaftaran."'>
                                            $ipk
                                        </span>
                                        <a id='tombol_".$nopendaftaran."' name='".$nopendaftaran."' onclick='tombol_ipk(this.name)' role='button'><span title='Edit IPK' class='glyphicon glyphicon-edit'></span></a>
                                            $editipk
                                        </td>
                                    </tr>
                                    <tr style='margin:0; width:100%; border-bottom-style: dashed; border-width: thin;'><td>No.Telp/HP</td><td>$telp</td></tr>
                                    <tr style='margin:0; width:100%; border-bottom-style: dashed; border-width: thin;'><td>Email</td><td>$email</td></tr>
                                    <tr style='margin:0; width:100%; border-bottom-style: dashed; border-width: thin;'><td>PT. Asal</td><td>$ptasal</td></tr>
                                    <tr style='margin:0; width:100%; border-bottom-style: dashed; border-width: thin;'><td>Akreditasi</td>
                                        <td><span id='akr_".$nopendaftaran."'>  
                                            $akr
                                            </span>
                                            <a id='tombol_akr_".$nopendaftaran."' name='".$nopendaftaran."' onclick='tombol_akr(this.name)' role='button'><span title='Edit Akreditasi' class='glyphicon glyphicon-edit'></span></a>
                                            <input id='edit_akr_".$nopendaftaran."'  style='display: none;' type='text' name='".$nopendaftaran."' class='form-control' value='".$akr."' onchange='save_akrS".$jenjang."(this.value, this.name);' />
                                            
                                        </td>
                                    </tr>
                                    <tr style='margin:0; width:100%; border-bottom-style: dashed; border-width: thin;'><td>TTL</td><td>".$data->orang->tempatLahir." , $tgllhir</td></tr>
                                    $dd
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
         
        </div>
    </div>
    $doaktf
    $aksi
    ";
        return $tp;
    }

    public function actionDetaildata()
    {
        
            if (Yii::$app->request->isAjax) {
            if (isset($_POST['data'])) {
                $nopendaftaran = $_POST['data']['nop'];
                
                echo $this->template($nopendaftaran);
            }
        } else
            return $this->redirect(['index','tahap'=>'20162']);
        

    }

    public function actionCreatebackup($fak='', $tahap='', $tahun = '')
    {
        //filter fakultas
        if ($fak =='Belum'){
            $allfak = 'Belum Pilih PS';
        }elseif ($fak!=''){
            $fak = explode(',',$fak);
            $allfak = join("_",$fak).'_';
        }else{
            $allfak='Semua Fakultas';
            $fak = [];
        }

        // filter periode
        if($tahap == '1'){
            $periode = 'Tahap 1';
        }elseif ($tahap == '2'){
            $periode = 'Tahap 2';
        }elseif ($tahap == '3'){
            $periode = 'Kelas Khusus';
        }elseif ($tahap == '4'){
            $periode = 'Kelas by Research';
        }else{
            $periode = 'Menunda';
            
        }
        $Exl = new Excel;
        $dirTemp = Yii::getAlias('@app').'/arsip/backup/';
        $filename = 'Backup_data_pelamar_'.$periode.'_'.$allfak.date('Y-m-d-h-m').'.xlsx';
        
        if (!file_exists($dirTemp)) {
            mkdir($dirTemp, 0777, true);
        }
        $objExl = $Exl->getExcelBackup($fak,$periode,$tahun);           
        $Exl->save($objExl,$dirTemp.$filename);
        $hasil =[
            'link'=>Excel::downloadUrl($dirTemp.$filename),
        ];
        if (Yii::$app->request->isAjax) 
            echo json_encode($hasil);
        else
            return json_encode($hasil);
    }
}
