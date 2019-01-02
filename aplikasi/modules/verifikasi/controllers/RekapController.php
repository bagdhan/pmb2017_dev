<?php

namespace app\modules\verifikasi\controllers;

use app\models\ModelData;
use app\models\NrpGenerator;
use Yii;
use app\models\Excel;
use yii\helpers\Json;
use yii\web\Controller;

class RekapController extends Controller
{
    public function actionIndex()
    {
        if (isset($_GET['rekapverifikasiexcel'])) {
            return self::createRekap1();
        }
        if (isset($_GET['rekapallexcel'])) {
            return self::createRekap2();
        }
        print_r(self::getAllverif(6));
        //die;
        return $this->render('index');
    }

    public function createRekap1()
    {
        $Exl = new Excel();
        $dirhasil = Yii::getAlias('@app') . '/arsip/verifikasi/';
        $filename = 'Rekap_verifikasi_' . date('YmdHis') . '.xlsx';

        if (!file_exists($dirhasil)) {
            mkdir($dirhasil, 0777, true);
        }

        $dirTemp = Yii::getAlias('@app') . '/arsip/template/';
        //$filename = 'temp3.xlsx';

        $objPHPExcel = $Exl->render($dirTemp, 'verifikasi.xlsx');

        ini_set('max_execution_time', 300);
        $sheet = $objPHPExcel->getSheet(0);

        $data = self::getAllverif(6);
        $fak = self::gerArrFak();

        $row = 5;

        // Header
        $sheet->setCellValue('N4', 'Status Penerimaan')
            ->setCellValue('O4', 'Usulan Pembiayaan')
            ->setCellValue('P4', 'Status Perkawinan')
            ->setCellValue('Q4', 'Pengakuan Beasiswa')
            ->setCellValue('R4', 'Tahapan Masuk')
            ->setCellValue('S4', 'Tahap Verivikasi');

        foreach ($data as $r => $item) {
            $nrp = new NrpGenerator();
            $tnrp = $nrp->getNrp($item['nopendaftaran']);
            $fakultas = isset($fak[substr($tnrp, 0, 1)]) ? $fak[substr($tnrp, 0, 1)] : (object)['inisial' => ''];
            $gender = $item['jeniskelamin'] == "0" ? "P" : "L";

            $st = [1 => "Biasa", 2 => "Percobaan", 3 => "Ditolak"];
            $statusmasuk = $st[$item['idHasilPleno']];

            $sponsor = $item['sumber_beasiswa'] == "biayasendiri" ? "Sendiri" : $item['pemberi_beasiswa'];

            $st2 = [1 => "Kawin", 2 => "Belum Kawin", 3 => "Janda/Duda", '' => ''];
            $statusKawin = $st2[$item['statuskawin']];

            $st3 = ['biayasendiri' => "Sendiri", 'departemen' => "Kerjasama IPB", 'yayasanptsswasta' => "Lainnya"];
            $beapengakuan = isset($st3[$item['sumber_beasiswa']]) ? $st3[$item['sumber_beasiswa']] : '';

            $sheet->setCellValue('A' . $row, $r + 1)
                ->setCellValue('B' . $row, $tnrp)
                ->setCellValue('C' . $row, $item['nama'])
                ->setCellValue('D' . $row, $item['stratamasuk'])
                ->setCellValue('E' . $row, $item['inisial'])
                ->setCellValue('F' . $row, $fakultas->inisial)
                ->setCellValue('G' . $row, $gender)
                ->setCellValue('H' . $row, $item['tempatlahir'] . ', ' . $item['tanggallahir'])
                ->setCellValue('I' . $row, $item['kewarganegaraan'])
                ->setCellValue('J' . $row, $item['instansi'])
                ->setCellValue('K' . $row, $item['email'])
                ->setCellValue('L' . $row, $item['mobilephone'])
                ->setCellValue('M' . $row, $item['nopendaftaran'])
                ->setCellValue('N' . $row, $statusmasuk)
                ->setCellValue('O' . $row, $sponsor)
                ->setCellValue('P' . $row, $statusKawin)
                ->setCellValue('Q' . $row, $beapengakuan)
                ->setCellValue('R' . $row, $item['kirimberkas'])
                ->setCellValue('S' . $row, $item['tahap']);
            $row++;
        }

        $Exl->save($objPHPExcel, $dirhasil . $filename);
        $hasil = [
            'link' => Excel::downloadUrl($dirhasil . $filename),
        ];

        return Json::encode($hasil);
    }

    // public function actionExport()
    // {
    //     print_r(self::createRekaplog());
    //     die;
    //     return $this->render('index');
    // }

    public function actionExport()
    {
        $Exl = new Excel();
        $dirhasil = Yii::getAlias('@app') . '/arsip/verifikasi/';
        $filename = 'Rekap_log_verifikasi_' . date('YmdHis') . '.xlsx';

        if (!file_exists($dirhasil)) {
            mkdir($dirhasil, 0777, true);
        }

        $dirTemp = Yii::getAlias('@app') . '/arsip/template/';
        //$filename = 'temp3.xlsx';

        $objPHPExcel = $Exl->render($dirTemp, 'rekaplog.xlsx');

        ini_set('max_execution_time', 300);
        $sheet = $objPHPExcel->getSheet(0);

        $data = self::getAllverif(6);
        $fak = self::gerArrFak();

        $row = 5;

        foreach ($data as $r => $item) {
            $nrp = new NrpGenerator();
            $tnrp = '';
            $tnrp = $nrp->getNrp($item['nopendaftaran']);
            $fakultas = $fak[substr($item['psPilihan'], 0, 1)];
            $meja[0] = '';
            $meja[1] = '';
            $meja[2] = '';
            $meja[3] = '';
            $meja[4] = '';
            $meja[5] = '';
            $meja[6] = '0';
            $log = Json::decode($item['log']);
            $jum = sizeof($log);

            $posisi = 0;
            for ($a = 1; $a <= $jum; $a++) {
                $posisi = substr($log[$a]['posisi'], 1);

                if ($posisi == '0') {
                    $meja[0] = $log[$a]['dateMasuk'];
                } else if ($posisi == '1') {
                    $meja[1] = $log[$a]['dateMasuk'];
                } else if ($posisi == '2') {
                    $meja[2] = $log[$a]['dateMasuk'];
                } else if ($posisi == '3') {
                    $meja[3] = $log[$a]['dateMasuk'];
                } else if ($posisi == '4') {
                    $meja[4] = $log[$a]['dateMasuk'];
                } else if ($posisi == '5') {
                    $meja[5] = $log[$a]['dateMasuk'];
                }
            }

            $sheet->setCellValue('A' . $row, $r + 1)
                ->setCellValue('B' . $row, $item['noantrian'])
                ->setCellValue('C' . $row, $tnrp)
                ->setCellValue('D' . $row, $item['nama'])
                ->setCellValue('E' . $row, $item['strata'])
                ->setCellValue('F' . $row, $item['inisial'])
                ->setCellValue('G' . $row, $fakultas->inisial)
                ->setCellValue('H' . $row, $item['tahap'])
                ->setCellValue('I' . $row, $meja[0])
                ->setCellValue('J' . $row, $meja[1])
                ->setCellValue('K' . $row, $meja[2])
                ->setCellValue('L' . $row, $meja[3])
                ->setCellValue('M' . $row, $meja[4])
                ->setCellValue('N' . $row, $meja[5]);
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

    public static function getAllverif($idtbl, $filter = '')
    {
        $db = Yii::$app->db;
        $sql = "select distinct
                    pop.*,
                    tbl_pleno2.*,
                    or_account.strata as stratamasuk,
                    or_account.kirimberkas,
                    tbl_verifikasi.*,
                    ps.inisial,
                    ps.mayor as pspilihan
                    
                 from po_pendaftaran as pop
                 inner join pin on pin.nopendaftaran=pop.nopendaftaran
                 inner join or_account on or_account.nopendaftaran=pop.nopendaftaran
                 
                 inner join tbl_pleno2 on tbl_pleno2.noPendaftaran=pop.noPendaftaran
                 left join po_mayor as ps on tbl_pleno2.psPilihan = ps.kode
                 
                 left join tbl_verifikasi on tbl_verifikasi.noPendaftaran=pop.nopendaftaran
                 
                 where pin.verifikasi =1 and tbl_pleno2.idHasilPleno <> 3
                  
                  and tbl_verifikasi.idTableVerifikasi = $idtbl; 
                 
                $filter
                
                order by tbl_pleno2.psPilihan;";
        $command = $db->createCommand($sql);
        $data = $command->queryAll();
        foreach ($data as $i => $d)
            $data[$i]['nama'] = ModelData::upfistarray($d['nama']);
        return $data;
    }

    public static function getAlldata($filter = '')
    {
        $db = Yii::$app->db;
        $sql = "select distinct
                    pop.*,
                    tbl_pleno2.*,
                    or_account.strata,
                    tbl_verifikasi.*,
                    ps.inisial,
                    ps.mayor as pspilihan
                    
                 from po_pendaftaran as pop
                 inner join pin on pin.nopendaftaran=pop.nopendaftaran
                 inner join or_account on or_account.nopendaftaran=pop.nopendaftaran
                 
                 inner join tbl_pleno2 on tbl_pleno2.noPendaftaran=pop.noPendaftaran
                 left join po_mayor as ps on tbl_pleno2.psPilihan = ps.kode
                 
                 LEFT join tbl_verifikasi on tbl_verifikasi.noPendaftaran=pop.nopendaftaran
                 
                 where pin.verifikasi =1 and tbl_pleno2.idHasilPleno <> 3 
                 
                $filter
                
                order by tbl_pleno2.psPilihan;";
        $command = $db->createCommand($sql);
        $data = $command->queryAll();
        foreach ($data as $i => $d)
            $data[$i]['nama'] = ModelData::upfistarray($d['nama']);
        return $data;
    }

    public function createRekap2()
    {
        $Exl = new Excel();
        $dirhasil = Yii::getAlias('@app') . '/arsip/verifikasi/';
        $filename = 'Rekap_Data_' . date('YmdHis') . '.xlsx';

        if (!file_exists($dirhasil)) {
            mkdir($dirhasil, 0777, true);
        }

        $dirTemp = Yii::getAlias('@app') . '/arsip/template/';
        //$filename = 'temp3.xlsx';

        $objPHPExcel = $Exl->render($dirTemp, 'verifikasi.xlsx');

        ini_set('max_execution_time', 300);
        $sheet = $objPHPExcel->getSheet(0);

        $data = self::getAlldata();
        $fak = self::gerArrFak();

        $row = 5;

        // Header
        $sheet->setCellValue('N4', 'Status Penerimaan')
            ->setCellValue('O4', 'Usulan Pembiayaan')
            ->setCellValue('P4', 'Status Perkawinan')
            ->setCellValue('Q4', 'Pengakuan Beasiswa')
            ->setCellValue('R4', 'Verifikasi');

        foreach ($data as $r => $item) {
            $nrp = new NrpGenerator();
            $tnrp = $nrp->getNrp($item['nopendaftaran']);
            $fakultas = isset($fak[substr($tnrp, 0, 1)]) ? $fak[substr($tnrp, 0, 1)] : (object)['inisial' => '-'];
            $gender = $item['jeniskelamin'] == "0" ? "P" : "L";

            $st = [1 => "Biasa", 2 => "Percobaan", 3 => "Ditolak"];
            $statusmasuk = $st[$item['idHasilPleno']];

            $sponsor = $item['sumber_beasiswa'] == "biayasendiri" ? "Sendiri" : $item['pemberi_beasiswa'];

            $st2 = [1 => "Kawin", 2 => "Belum Kawin", 3 => "Janda/Duda"];
            $statusKawin = $st2[$item['statuskawin']];

            $st3 = ['biayasendiri' => "Sendiri", 'departemen' => "Kerjasama IPB", 'yayasanptsswasta' => "Lainnya"];
            $beapengakuan = isset($st3[$item['sumber_beasiswa']]) ? $st3[$item['sumber_beasiswa']] : '';

            $statver = $item['idTableVerifikasi'] == null ? "belum" : "Meja id" . $item['idTableVerifikasi'];

            $sheet->setCellValue('A' . $row, $r + 1)
                ->setCellValue('B' . $row, $tnrp)
                ->setCellValue('C' . $row, $item['nama'])
                ->setCellValue('D' . $row, $item['strata'])
                ->setCellValue('E' . $row, $item['inisial'])
                ->setCellValue('F' . $row, $fakultas->inisial)
                ->setCellValue('G' . $row, $gender)
                ->setCellValue('H' . $row, $item['tempatlahir'] . ', ' . $item['tanggallahir'])
                ->setCellValue('I' . $row, $item['kewarganegaraan'])
                ->setCellValue('J' . $row, $item['instansi'])
                ->setCellValue('K' . $row, $item['email'])
                ->setCellValue('L' . $row, $item['mobilephone'])
                ->setCellValue('M' . $row, $item['nopendaftaran'])
                ->setCellValue('N' . $row, $statusmasuk)
                ->setCellValue('O' . $row, $sponsor)
                ->setCellValue('P' . $row, $statusKawin)
                ->setCellValue('Q' . $row, $beapengakuan)
                ->setCellValue('R' . $row, $statver);
            $row++;
        }

        $Exl->save($objPHPExcel, $dirhasil . $filename);
        $hasil = [
            'link' => Excel::downloadUrl($dirhasil . $filename),
        ];

        return Json::encode($hasil);
    }

    /**
     * @return array
     */
    public function actionBackup()
    {
        $Exl = new Excel();
        $dirhasil = Yii::getAlias('@app') . '/arsip/backup/';
        $filename = 'Rekap_Data_' . date('YmdHis') . '.xlsx';

        if (!file_exists($dirhasil)) {
            mkdir($dirhasil, 0777, true);
        }

        $dirTemp = Yii::getAlias('@app') . '/arsip/template/';

        $objPHPExcel = $Exl->render($dirTemp, 'temp5.xlsx');

        ini_set('max_execution_time', 300);
        $sheet = $objPHPExcel->getSheet(0);

        $data = ModelData::getBackup();
        $fak = self::gerArrFak();

        $datapleno = self::getDataPleno2();
        $dataplsatu = self::getDataPleno1();

        $dataverifikasi = self::getDataVerifikasi();

        $row = 6;
        $nrp = new NrpGenerator();
        $awalkodebayar = [
            'A' => '01','B' => '02','C' => '03','D' => '04','E' => '05','F' => '06',
            'G' => '07','H' => '08','I' => '09','J' => '10','K' => '11','L' => '12',
            'M' => '13','N' => '14','O' => '15','P' => '16','Q' => '17','R' => '18',
        ];

        $st = [1 => "Biasa", 2 => "Percobaan", 3 => "Ditolak", '' => ''];

        $st1 = [
            1 => " Tahap 1",
            2 => " Tahap 2",
            3 => " Kelas Khusus",
            8 => "Pendaftaran PMDSU",
            0 => "Belum di tetapkan",
            4 => "Tidak memenuhi",
            9 => "Kelas Khusus 2017",
            10 => "Mahasiswa Asing",
            '' => ''
        ];

        $thverivarr = [
            1 => "vTahap 1",
            2 => "vTahap 2",
            3 => "vSusulan",
            4 => "vKlsKhusus",
            5 => "vKlsKhusus2",
            '' => "",
        ];

        $statverivarr = [
            1 => "M0",
            2 => "M1",
            3 => "M2",
            4 => "M3",
            5 => "M4",
            6 => "terima",
            7 => "M6",
            8 => "tolak",
            '' => "",
        ];

        // Header
        $sheet->setCellValue('A3', '')
            ->setCellValue('J5', 'Penerimaan')
            ->setCellValue('L5', 'L/P')
            ->setCellValue('G5', 'PS 1')
            ->setCellValue('H5', 'PS 2')
            ->setCellValue('M5', 'Kode Bayar')
            ->setCellValue('N5', 'Tahap Verifikasi')
            ->setCellValue('O5', 'Status Verifikasi')
            ->setCellValue('P5', 'Diterima Di')
            ->setCellValue('Q5', 'pilihan ke')
            ->setCellValue('R5', 'Fakultas')
            ->setCellValue('S5', 'Status Penerimaan');

        $sheet->removeRow(6, 2);
        foreach ($data as $r => $item) {

            $tnrp = $nrp->getNrp($item['nopendaftaran']);
            $kodebayar = '';
            if ($tnrp != '') {
                $kodenrp = substr($tnrp, 1);
                $kodebayar = isset($awalkodebayar[substr($tnrp, 0, 1)]) ? $awalkodebayar[substr($tnrp, 0, 1)] . $kodenrp : '';
            }

            $fakultas = isset($fak[substr($tnrp, 0, 1)]) ? $fak[substr($tnrp, 0, 1)] : (object)['inisial' => '-'];
            $gender = $item['jeniskelamin'] == "0" ? "P" : "L";

            $arrpleno = isset($datapleno[$item['nopendaftaran']]) ? $datapleno[$item['nopendaftaran']] : [];
            $arrplsatu = isset($dataplsatu[$item['nopendaftaran']]) ? $dataplsatu[$item['nopendaftaran']] : [];

            $ket = '';
            $pspilihan = '';
            $tempatps = '';
            $fakps = '';

            $statusPenerimaan = $item['kirimberkas'] == 4 ? $st[3] : '';

            foreach ($arrpleno as $pl) {
                if ($pl['ps'] == $item['mayor1']) {
                    $ket .= "  Status prodi 1 : " . $st[$pl['idhasil']] . ";";
                    if($pl['idhasil'] != 3 || sizeof($arrpleno) == 1)
                        $statusPenerimaan = $st[$pl['idhasil']];
                }
                if ($pl['ps'] == $item['mayor2'] && $item['mayor2'] != $item['mayor1']) {
                    $ket .= "  Status prodi 2 : " . $st[$pl['idhasil']] . ";";
                    $statusPenerimaan = $st[$pl['idhasil']];
                }

                if ($pl['idhasil'] != 3) {
                    if ($pl['ps'] == $item['mayor1']) {
                        $pspilihan = $item['inisial1'];
                        $tempatps = 'ps1';
                        $fakps = substr($pl['ps'], 0, 1);
                    }
                    if ($pl['ps'] == $item['mayor2'] && $item['mayor2'] != $item['mayor1']) {
                        $pspilihan = $item['inisial2'];
                        $tempatps = 'ps2';
                        $fakps = substr($pl['ps'], 0, 1);
                    }
                }
            }

            foreach ($arrplsatu as $pl1) {
                if ($pl1['idhasil'] == 3) {
                    $ket .= " Ditolak di Pleno 1 ";
                    $statusPenerimaan = $st[3];
                }
            }

            $tahapver = '';
            $statver = '';

            if (isset($dataverifikasi[$item['nopendaftaran']])) {
                $arrver = $dataverifikasi[$item['nopendaftaran']];
                $tahapver = $thverivarr[$arrver['tahap']];
                $statver = $statverivarr[$arrver['meja']];
            }

            $penerimaan = $st1[$item['kirimberkas']];

            $sheet->setCellValue('A' . $row, $r + 1)
                ->setCellValue('B' . $row, $item['nopendaftaran'])
                ->setCellValue('C' . $row, $tnrp)
                ->setCellValue('D' . $row, \app\models\ModelData::upfistarray($item['nama']))
                ->setCellValue('E' . $row, \app\models\ModelData::upfistarray($item['jalan'] . ' Rt. ' .
                        $item['rt'] . '/' . $item['rw'] . ' ' . $item['keldesa'] . ', ' .
                        $item['kecamatan'] . ', ' . $item['kabkodya'] . ', ' . $item['propinsi'] . ', ' .
                        $item['kodepos']) . '. HP ' . $item['mobilephone'])
                ->setCellValue('F' . $row, $item['strata'])
                ->setCellValue('G' . $row, $item['pilih1'] . ' (' . $item['inisial1'] . ')')
                ->setCellValue('H' . $row, $item['pilih2'] . ' (' . $item['inisial2'] . ')')
                ->setCellValue('I' . $row, $fakultas->inisial)
                ->setCellValue('J' . $row, $penerimaan)
                ->setCellValue('K' . $row, $ket)
                ->setCellValue('L' . $row, $gender)
                ->setCellValue('M' . $row, $kodebayar)
                ->setCellValue('N' . $row, $tahapver)
                ->setCellValue('O' . $row, $statver)
                ->setCellValue('P' . $row, $pspilihan)
                ->setCellValue('Q' . $row, $tempatps)
                ->setCellValue('R' . $row, $fakps)
                ->setCellValue('S' . $row, $statusPenerimaan);
            $row++;
        }

        $Exl->save($objPHPExcel, $dirhasil . $filename);
        $hasil = [
            'link' => Excel::downloadUrl($dirhasil . $filename),
        ];

        return Json::encode($hasil);
    }

    public static function getDataPleno2($filter = '')
    {
        $db = Yii::$app->db;
        $sql = "SELECT * FROM `tbl_pleno2`
                $filter
                order by tbl_pleno2.idHasilPleno;";
        $command = $db->createCommand($sql);
        $data = $command->queryAll();
        $out = [];
        foreach ($data as $i => $d)
            $out[$d['noPendaftaran']][] = [
                'nopen' => $d['noPendaftaran'],
                'ps' => $d['psPilihan'],
                'idhasil' => $d['idHasilPleno'],
            ];
        return $out;
    }

    public static function getDataPleno1($filter = '')
    {
        $db = Yii::$app->db;
        $sql = "SELECT * FROM `tbl_pleno1`
                $filter
                order by tbl_pleno1.idHasilPleno;";
        $command = $db->createCommand($sql);
        $data = $command->queryAll();
        $out = [];
        foreach ($data as $i => $d)
            $out[$d['noPendaftaran']][] = [
                'nopen' => $d['noPendaftaran'],
                'ket' => $d['keterangan'],
                'idhasil' => $d['idHasilPleno'],
            ];
        return $out;
    }

    public static function getDataVerifikasi($filter = '')
    {
        $db = Yii::$app->db;
        $sql = "SELECT * FROM tbl_verifikasi
                $filter
                order by tbl_verifikasi.tahap;";
        $command = $db->createCommand($sql);
        $data = $command->queryAll();
        $out = [];
        foreach ($data as $i => $d)
            $out[$d['noPendaftaran']] = [
                'nopen' => $d['noPendaftaran'],
                'meja' => $d['idTableVerifikasi'],
                'tahap' => $d['tahap'],
            ];
        return $out;
    }

}
