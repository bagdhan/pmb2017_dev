<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 6/7/2016
 * Time: 3:21 PM
 */

namespace app\models;

use app\modules\admin\models\GenNrp;
use app\models\Pendaftaran;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class NrpGenerator
 *
 * sumber : http://ab_saleh.staff.ipb.ac.id/2012/02/17/penomoran-mahasiswa-6/
 *
 *
 *
 * @package app\models
 */
class NrpGenerator extends Model
{
    public static function gen()
    {
        $gen = [];
        $kodeProdi = '';
        $data = self::getDataGenNRP();
        foreach ($data as $d) {
            $model = new GenNrp;
            $model->noPendaftaran = $d->noPendaftaran;
            $penProg = $d->pendaftaranHasProgramStudis;
            foreach($penProg as $pen){
                $prosesSidang = isset($pen->prosesSidangs)? $pen->prosesSidangs : null ; 
                foreach($prosesSidang as $data){
                    if($data->hasilKeputusan_id == 4 || $data->hasilKeputusan_id == 5){
                       $kodeProdi =  $data->pendaftaranHasProgramStudi->programStudi->kode;
                    }
                }
            }
            $model->tahunMasuk = date('Y');
            $model->kodeProdi = $kodeProdi;
            $model->kodeKhusus = 0;
            $model->noUrut = self::getNoUrut($model->tahunMasuk, $model->kodeProdi);
            $program_id = isset($d->manajemenJalurMasuk->program_id)? $d->manajemenJalurMasuk->program_id : 1;
            $model->kodeMasuk = self::getKodeMasuk($program_id);

            $model->lockNrp = 0;
            $thn = self::getDuaDigitThn($model->tahunMasuk);
            $nourut = self::getStringNoUrut($model->noUrut);
            echo $model->kodeProdi . $thn . $model->kodeKhusus . $nourut . $model->kodeMasuk . '<br>';
            if (!isset($gen[$model->noPendaftaran]))
                if ($model->save(false)) {
                    $gen[$model->noPendaftaran] = $model->kodeProdi . $thn . $model->kodeKhusus . $nourut . $model->kodeMasuk;
                } else
                    return false;
            else
                $gen[$model->noPendaftaran]['msg'] = 'error' . $d->noPendaftaran . '<br>';
        }
        return $gen;
    }

    public static function getKodeMasuk($kode)
    {
        $listArray = [
            1 => 1, // reguler
            2 => 4, // kelas khusus
            3 => 5, // kelas profesional
            4 => 6, // sinergi
            5 => 8, // PMDSU
            6 => 0, // research student
            7 => 7, // student exchange
            8 => 3, // kerjasama
            9 => 2, // by research
            10 => 9, //re-Entry
        ];
        return $listArray[$kode];
    }

    public function lockAllIn($allid)
    {
        $hasil = false;
        $log = [];
        $data = GenNrp::find()->where("id IN ($allid)")->all();
        foreach ($data as $d) {
            $d->locknrp = 1;
            if ($d->save()) {
                $hasil = true;
                $log['error'][]=$d->id;
            } else {
                $hasil = false;
                $log['error'][]=$d->id;
            }
        }
        return $hasil;
    }

    private static function getNoUrut($thn, $kodeprodi)
    {
        $data = GenNrp::find()->where(['kodeProdi' => $kodeprodi, 'tahunMasuk' => $thn])->max('noUrut');
        if (empty($data))
            return 1;
        else
            return $data + 1;
    }

    public function getDuaDigitThn($thn)
    {
        return substr($thn, 2, 2);
    }

    public function getStringNoUrut($no)
    {
        return substr_replace('00', $no, (strlen($no) - 2), strlen($no));
    }

    public function getNrp($noPendaftaran)
    {
        $model = GenNrp::findOne(['noPendaftaran' => $noPendaftaran]);
        if (empty($model)){
            return '';
        }
        $thn = $this->getDuaDigitThn($model->tahunMasuk);
        $nourut = $this->getStringNoUrut($model->noUrut);
        return $model->kodeProdi . $thn . $model->kodeKhusus . $nourut . $model->kodeMasuk;
    }

    // public static function getDataGenNRP()
    // {
    //     $db = Yii::$app->db;
    //     $sql = "select 
    //                 pop.nopendaftaran,
    //                 pop.nama,
    //                 or_account.kirimberkas,
    //                 pl2.psPilihan,
    //                 pl2.idHasilPleno,
    //                 myr.inisial,
    //                 myr.mayor as namamayor
				
    //              from po_pendaftaran as pop
    //              inner join pin on pin.nopendaftaran=pop.nopendaftaran
    //              inner join or_account on or_account.nopendaftaran=pop.nopendaftaran
    //              inner join tbl_pleno2 as pl2 on pl2.nopendaftaran = pop.nopendaftaran
    //              left join po_mayor as myr on pl2.psPilihan = myr.kode
                
    //             where pin.verifikasi =1 and or_account.kirimberkas = 8 and pl2.idHasilPleno < 3
    //               and pop.nopendaftaran NOT IN (SELECT no_pendaftaran FROM `tbl_gen_nrp`); ";
    //     $command = $db->createCommand($sql);
    //     $data = $command->queryAll();
    //     return $data;
    // }

    public static function getDataGenNRP()
    {
        $listid = ArrayHelper::getColumn(GenNrp::find()
                ->asArray()
                ->all(),'noPendaftaran');
        // print_r($listid);die();
        $data = Pendaftaran::find()
                                ->joinWith(['pinVerifikasi p'=> function($q){$q->where(['p.status'=>1]);},
                                            'paketPendaftaran a',
                                            'manajemenJalurMasuk m',
                                            'pendaftaranHasProgramStudis ph',
                                            'pendaftaranHasProgramStudis.programStudi ps',
                                            'pendaftaranHasProgramStudis.prosesSidangs pps'=>function($q){$q->where('pps.hasilKeputusan_id = 4 or pps.hasilKeputusan_id = 5');},
                                            'pendaftaranHasProgramStudis.prosesSidangs.sidang s'=>function($q){$q->where(['s.jenisSidang_id'=>2]);}]
                                            )
                                ->where('pendaftaran.noPendaftaran NOT IN (SELECT noPendaftaran FROM genNrp)')->orderBy(['ps.kode'=>SORT_ASC,'pps.id'=>SORT_ASC])->all();
        
        return $data;
    }
}