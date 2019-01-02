<?php

namespace app\modules\verifikasi\controllers;

use app\modules\verifikasi\models\Absensi;
use app\modules\verifikasi\models\Absenm4Form;
use app\modules\verifikasi\models\KuForm;
use Yii;
use app\models\ModelData;
use app\models\NrpGenerator;
use app\models\Excel;
use yii\helpers\Json;


class Absenm4Controller extends \yii\web\Controller
{
    public function actionIndex()
    {
        $absenm4Form = new Absenm4Form();

        $absenm4Form->addForm(Yii::$app->request->queryParams);

        return $this->render('index', [
            'absenm4Form' => $absenm4Form,
        ]);
    }

    public function actionKuliahumum()
    {
        $absenm4Form = new KuForm();

        $absenm4Form->addForm(Yii::$app->request->queryParams);

        return $this->render('kuliahum', [
            'absenm4Form' => $absenm4Form,
        ]);
    }

    public function actionHp($aa)
    {
        $kodeprodi = substr($this->inputan, 0, 4);
        $thmasuk = '20'.substr($this->inputan, 4, 2);
        $kodekhs = substr($this->inputan, 6, 1);
        $nourut = substr($this->inputan, 7, 2);
        $kodemasuk = substr($this->inputan, 9, 1);

        $datanrp = GenNrp::findOne([
            'kodeprodi' => $kodeprodi ,
            'tahun_masuk' => $thmasuk,
            'kode_khusus' => $kodekhs,
            'nourut' => $nourut,
            'kode_masuk' => $kodemasuk,
        ]);

        if (empty($datanrp)) {
            $this->noPendaftaran = $this->inputan;
        } else {
            $this->noPendaftaran = $datanrp->no_pendaftaran;
        }

        $daftarabsen = Absensi::findOne(['identity' => $this->noPendaftaran, 'even' => 'KuliahUmum']);

        if (empty($daftarabsen)) {
            $daftarabsen = new Absensi();

            $daftarabsen->identity = $this->noPendaftaran;
            $daftarabsen->even = "KuliahUmum";

            if ($daftarabsen->save()) {
                die("<h1>OK</h1>");
            } else {
                die("<h1>Tidak</h1>");
            }

        } else {
            die("<h1>sudah daftar</h1>");
        }
        return $this->render('kuliahum', [
            'absenm4Form' => $absenm4Form,
        ]);
    }

    public function actionCetakabsen()

    {
        $Exl = new Excel();
        $dirhasil = Yii::getAlias('@app') . '/arsip/absen/';
        $filename = 'Daftar_hadir_kuliah_umum' . date('YmdHis') . '.xlsx';

        if (!file_exists($dirhasil)) {
            mkdir($dirhasil, 0777, true);
        }

        $dirTemp = Yii::getAlias('@app') . '/arsip/template/';
        //$filename = 'temp3.xlsx';

        $objPHPExcel = $Exl->render($dirTemp, 'daftar_hadir_kuliah_umum.xlsx');

        ini_set('max_execution_time', 300);
        $sheet = $objPHPExcel->getSheet(0);

        $data = self::getAllverif();
        $fak = self::gerArrFak();

        $row = 5;

        foreach ($data as $r => $item) {
            $inisialfak = '';
            $nrp = new NrpGenerator();
            $tnrp = $nrp->getNrp($item['nopendaftaran']);
            if($tnrp!=''){
            $fakultas = $fak[substr($tnrp,0,1)];
            $inisialfak = $fakultas->inisial;
            }
          

            $sheet->setCellValue('A' . $row, $r + 1)
                ->setCellValue('B' . $row, $tnrp)
                ->setCellValue('C' . $row, $item['nama'])
                ->setCellValue('D' . $row, $item['strata'])
                ->setCellValue('E' . $row, $item['inisial'])
                ->setCellValue('F' . $row, $inisialfak)
                ->setCellValue('G' . $row, $item['dateCreate']);
            $row++;
        }

        $Exl->save($objPHPExcel, $dirhasil . $filename);
        $hasil = [
            'link' => Excel::downloadUrl($dirhasil . $filename),
        ];

        return Json::encode($hasil);
    }

    public static function gerArrFak()
    {
        $data = ModelData::getAllFakultas();
        $exp = [];
        foreach ($data as $item) {
            $tmp['nama'] = $item['fakultas'];
            $tmp['kode'] = $item['kode'];
            $tmp['inisial'] = $item['inisial'];
            $exp[$item['kode']] = (object)$tmp;
        }
        return $exp;
    }
    
    public static function getAllverif($filter = '')
    {
        $db = \Yii::$app->db;
        $sql = "select distinct
                    pop.*,
                    tbl_pleno2.*,
                    or_account.strata,
                    tbl_absensi.*,
                    ps.inisial,
                    ps.mayor as pspilihan
                    
                 from po_pendaftaran as pop
                 inner join pin on pin.nopendaftaran=pop.nopendaftaran
                 inner join or_account on or_account.nopendaftaran=pop.nopendaftaran
                 
                 inner join tbl_pleno2 on tbl_pleno2.noPendaftaran=pop.noPendaftaran
                 left join po_mayor as ps on tbl_pleno2.psPilihan = ps.kode
                 
                 left join tbl_absensi on tbl_absensi.identity=pop.nopendaftaran
                 
                 where pin.verifikasi =1 and tbl_pleno2.idHasilPleno <> 3
                 
                $filter
                
                order by tbl_pleno2.psPilihan;";
        $command = $db->createCommand($sql);
        $data = $command->queryAll();
        foreach ($data as $i => $d)
            $data[$i]['nama'] = \app\models\ModelData::upfistarray($d['nama']);
        return $data;
    }

}
