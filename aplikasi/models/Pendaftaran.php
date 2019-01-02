<?php
/**
 * Created by sublimetext.
 * User: doni
 * Date: 23/1/2017  dd/mm/yyyy
 * Time: 15:20
 */


namespace app\models;

use app\modelsDB\PinReference;
use Yii;
use app\modelsDB\PendaftaranHasProgramStudi;
use app\modelsDB\Pendidikan;
use app\models\Fakultas;
use app\modelsDB\SyaratBerkas;
use app\modelsDB\SyaratTambahan;
use app\modules\pendaftaran\models\PinVerifikasi;
use yii\web\NotFoundHttpException;
use yii\web\NotAcceptableHttpException;

/**
 * This is the model class for table "pendaftaran".
 *
 *
 * @property bool $statusVerifikasiPin
 * @property PinVerifikasi $pinVerifikasi
 */
class Pendaftaran extends \app\modelsDB\Pendaftaran
{

    public function beforeSave($insert)
    {
        CostumDate::tiemZone();
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->waktuBuat = date('Y-m-d H:i:s');
                $this->waktuUbah = date('Y-m-d H:i:s');
            } else {
                $this->waktuUbah = date('Y-m-d H:i:s');
            }

            return SecurityChack::checkSaveModel($this);
        }
        return false;
    }

    public function generateNoPendaftaran()
    {
        $output['token'] = Yii::$app->security->generateRandomString(64);
        $output['pin'] = sprintf('%06d', mt_rand(0,999999)) . sprintf('%06d', mt_rand(0,999999));

//        $this->noPendaftaran = substr(date('Y'),-2) . sprintf('%06d', (self::find()->count() + 1));

        $this->noPendaftaran = self::getReference();
    }

    public static function getReference()
    {
        $ref = PinReference::findOne(['use' => 0]);

        if (empty($ref))
            throw new NotFoundHttpException('Persediaan No Pendaftaran Habis');

        $ref->use = 1;
        $ref->save(false);

        $r = self::findOne(['noPendaftaran' => $ref->noPendaftaran]);
        if (empty($r)) {
            $nopen =  $ref->noPendaftaran;
        } else {
            $nopen = self::getReference();
        }


        return $nopen;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPinVerifikasi()
    {
        return $this->hasOne(PinVerifikasi::className(), ['noPendaftaran' => 'noPendaftaran']);
    }


    public static function upfistarray($input){
        $pca = explode(' ', $input);
        $g='';
        foreach ($pca as $p) {
            if (strlen($p) > 3)
                $g[] = ucfirst(strtolower($p));
            else
                $g[] = $p;
        }
        return join(' ',$g);
    }

    public function prodike($ke){
        $prodi = PendaftaranHasProgramStudi::find()->where(['pendaftaran_noPendaftaran'=>$this->noPendaftaran,'urutan'=>$ke])->one();
        if(isset($prodi)){
            return $prodi;
        }else{
            return null;
        }      
    }

    public function ipksebelumnya($strata){
        if($strata == '3'){
            $strata = '2';
        }else{
            $strata = '1';
        }
        $pendidikan = Pendidikan::find()->where(['orang_id'=>$this->orang_id,'strata'=>$strata])->one();    
        if(isset($pendidikan)){
            $data['ipk'] = $pendidikan->ipk;
            $data['ipkasal'] = $pendidikan->ipkAsal;
            return $data;
        }else{
            $data['ipk'] = null;
            $data['ipkasal'] = null;
            return $data;
        }
        
    }

    public function pendidikansebelumnya($strata){
        if($strata == '3'){
            $strata = '2';
        }else{
            $strata = '1';
        }
        $pendidikan = Pendidikan::find()->where(['orang_id'=>$this->orang_id,'strata'=>$strata])->one();    
        if(isset($pendidikan)){
            return $pendidikan;
        }else{
            return null;
        }
        
    }

    public function verifberkas(){
        $syaratberkas = SyaratBerkas::find()->where(['pendaftaran_noPendaftaran'=>$this->noPendaftaran])->andWhere('status = 1')->one();
            if(isset($syaratberkas)){
                return true;
            }else{
                return false;
            }
        
        
    }

    public function tpa_score(){
        $syarattambahan = SyaratTambahan::find()->where(['jenisSyaratTambahan_id'=>1,'pendaftaran_noPendaftaran'=>$this->noPendaftaran])->one();
        if(isset($syarattambahan)){
            return $syarattambahan->score;
        }else{
            return 0;
        }
    }


    public static function getVerifikasi($kode,$strata,$periode)
    {
        $_POST['fak'] = $kode;
        $_POST['strata'] = $strata;
        $_POST['periode'] = $periode;
        $model = Pendaftaran::find();
                               
        if($kode!=''){
            if($strata != ''){
                $data = $model->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['periode']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where(['substring(ps.kode,1,1)'=>$_POST['fak'],'strata'=>$_POST['strata']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s']
                                         )
                                ->where('p.noPendaftaran > 17010001')->orderBy('p.noPendaftaran ASC')->all();
            }else{
                $data = $model->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['periode']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.urutan'=>1]);},
                                            'pendaftaranHasProgramStudis.programStudi ps'=>function($q){$q->where(['substring(ps.kode,1,1)'=>$_POST['fak']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s']
                                         )
                                ->where('p.noPendaftaran > 17010001')->orderBy('p.noPendaftaran ASC')->all();
            }
        }else{
            if($strata != ''){
                $data = $model->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['periode']]);},
                                            'pendaftaranHasProgramStudis ph'=>function($q){$q->where(['ph.pendaftaran_noPendaftaran'=>null]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s']
                                         )
                                ->where('p.noPendaftaran > 17010001')->orderBy('p.noPendaftaran ASC')->all();
            }else{
                $data = $model->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a'=> function($q){$q->where(['a.uniqueUrl'=>$_POST['periode']]);},
                                            'pendaftaranHasProgramStudis.prosesSidangs pps',
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s']
                                         )
                                ->where('p.noPendaftaran > 17010001')->orderBy('p.noPendaftaran ASC')->all();
            }
        }
        
        return $data;
    }

    public function cek_menunda(){
        $tahunSekarang = date('Y');
        $tahun = $this->paketPendaftaran->tahun;
        $menunda = '';
        if($tahun < $tahunSekarang && ($tahunSekarang-1)==$tahun){

            $finalStatus = $this->finalStatuses;
            $menunda = isset($finalStatus)? 1 : 0;
        }

        if($menunda===0){
          throw new NotAcceptableHttpException("Akses anda ditolak, <br> untuk lebih jelasnya  silahkan hubungi 0251 8628448. ");
        }
            if($menunda==''){
                return 0;
            }else{
                return 1;
            }
        
        
    }

    public function getStatusVerifikasiPin()
    {
        return $this->pinVerifikasi != null && $this->pinVerifikasi->status == 1;
    }
}