<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 5/13/2017
 * Time: 11:50 AM
 */

namespace app\modules\admin\controllers;

use app\models\Orang;
use app\models\SyaratTambahan;
use app\modelsDB\FinalStatus;
use app\modelsDB\OrangHasAlamat;
use app\modelsDB\PendaftaranHasProgramStudi;
use app\modelsDB\Pendidikan;
use app\modules\pendaftaran\models\Identitas;
use app\modules\pendaftaran\models\Kontak;
use app\modules\pendaftaran\models\lengkapdata\Alamat;
use app\modules\pendaftaran\models\lengkapdata\DataPribadi;
use app\modules\pendaftaran\models\lengkapdata\S1;
use app\modules\pendaftaran\models\lengkapdata\S2;
use app\modules\pendaftaran\models\PinVerifikasi;
use app\modules\pleno\models\Pendaftar;
use app\modules\pleno\models\ProsesSidang;
use app\models\ProgramStudi;
use app\modelsDB\DesaKelurahan;
use app\modelsDB\Institusi;
use app\modules\pleno\models\Sidang;
use Yii;
use yii\web\Controller;

class MigrasiDataController extends Controller
{

    public function actionProses()
    {
        $posts = Yii::$app->db->createCommand("
        select 
                	pop.*,
                	pl2.*,
                    acc.kirimberkas,
                    acc.strata,
                    p.dateverifikasi
                    
                 from pmbpasca_2016_2017.po_pendaftaran as pop
                 inner join pmbpasca_2016_2017.pin as p on p.nopendaftaran=pop.nopendaftaran
                 inner join pmbpasca_2016_2017.or_account as acc on acc.nopendaftaran=pop.nopendaftaran
                
                 left join pmbpasca_2016_2017.tbl_pleno2 as pl2 on pl2.nopendaftaran = pop.nopendaftaran
                 
                where p.verifikasi = 1 and pl2.idHasilPleno <> 3 and pop.nopendaftaran in (16013369, 16013368, 
                16013406, 16013005, 16013395, 16012902, 16013372, 16013370, 16013371, 16013394);
        ")
            ->queryAll();

//        print_r($posts);
//        die;

        foreach ($posts as $post) {
            $dataPribadi = new Orang([
                'nama' => $post['nama'],
//                'KTP' => $post['noktp'],
                'tempatLahir' => $post['tempatlahir'],
                'tanggalLahir' => $post['tanggallahir'],
                'jenisKelamin' => $post['jeniskelamin'],
                'statusPerkawinan_id' => $post['statuskawin'],
                'negara_id' => 1,
            ]);

            $dataPribadi->save(false);

            $dataPribadi = DataPribadi::findOne($dataPribadi->id);
            $dataPribadi->namagadisibu = $post['statuskawin'];
            $dataPribadi->gelarDepan = $post['gelardepan'];
            $dataPribadi->gelarBelakang = $post['gelarbelakang'];
            $dataPribadi->save(false);

            $orangId = $dataPribadi->id;

            $orghasAlt = OrangHasAlamat::findOne([
                'orang_id' => $dataPribadi->id,
            ]);

            if (empty($orghasAlt)) {
                $alamat = new Alamat([
                    'jalan' => $post['jalan'],
                    'kodePos' => $post['kodepos'],
                    'rt' => $post['rt'],
                    'rw' => $post['rw'],
                    'desaKelurahan_kode' => $this->findKodeDesa($post['keldesa'], $post['kecamatan']),
                ]);

                $alamat->save(false);

                $orghasAlt = new OrangHasAlamat([
                    'orang_id' => $dataPribadi->id,
                    'alamat_id' => $alamat->id,
                ]);

                $orghasAlt->save(false);
            } else {
                $alamat = Alamat::findOne($orghasAlt->alamat_id);

                $alamat->jalan = $post['jalan'];
                $alamat->kodePos = $post['kodepos'];
                $alamat->rt = $post['rt'];
                $alamat->rw = $post['rw'];
                $alamat->desaKelurahan_kode = $this->findKodeDesa($post['keldesa'], $post['kecamatan']);

                $alamat->save(false);
            }

            $identitas = Identitas::findOne([
                'orang_id' => $orangId,
                'jenisIdentitas_id' => 1,
            ]);
            if (empty($identitas))
                $identitas = new Identitas([
                    'orang_id' => $orangId,
                    'jenisIdentitas_id' => 1,
                ]);

            $identitas->identitas = $post['noktp'];
            $identitas->save(false);

            $hp = Kontak::findOne([
                'orang_id' => $orangId,
                'jenisKontak_id' => 2,
            ]);
            if (empty($hp))
                $hp = new Kontak([
                    'orang_id' => $orangId,
                    'jenisKontak_id' => 2,
                ]);

            $hp->kontak = $post['mobilephone'];
            $hp->save(false);

            if ($post['phone'] != null) {
                $tlp = Kontak::findOne([
                    'orang_id' => $orangId,
                    'jenisKontak_id' => 3,
                ]);
                if (empty($tlp))
                    $tlp = new Kontak([
                        'orang_id' => $orangId,
                        'jenisKontak_id' => 3,
                    ]);
                $tlp->kontak = $post['phone'];
                $tlp->save(false);
            }

            $email = Kontak::findOne([
                'orang_id' => $orangId,
                'jenisKontak_id' => 1,
            ]);
            if (empty($email))
                $email = new Kontak([
                    'orang_id' => $orangId,
                    'jenisKontak_id' => 1,
                ]);
            $email->kontak = $post['email'];

            $email->save(false);

            if ($post['emailalternatif'] != '') {
                $ch = Kontak::find()->where([
                    'orang_id' => $orangId,
                    'jenisKontak_id' => 1,
                ])->all();
                if (sizeof($ch) < 2) {
                    $email2 = new Kontak();
                    $email2->jenisKontak_id = 1;
                    $email2->kontak = $post['emailalternatif'];
                    $email2->orang_id = $dataPribadi->id;
                    $email2->save(false);
                }
            }

            $pens1 = Pendidikan::findOne([
                'orang_id' => $orangId,
                'strata' => 1,
            ]);

            if (empty($pens1)) {
                $pens1 = new Pendidikan([
                    'orang_id' => $orangId,
                    'strata' => 1,
                ]);
            }
            $pens1->programStudi = $post['s1programstudi'];
            $pens1->akreditasi = $post['s1akreditasi'];
            $pens1->gelar = substr($post['s1gelar'], 0, 20);
            $pens1->jumlahSKS = $post['s1jumlahsks'];
            $pens1->ipk = $post['s1ipk'];
            $pens1->ipkAsal = $post['s1ipkasal'];
            $pens1->tahunMasuk = '';
            $pens1->tanggalLulus = $post['s1tanggallulus'];
            $pens1->noIjazah = '';
            $pens1->judulTA = $post['judul_skripsi'];
            $pens1->institusi_id = $this->findInstitusiId($post['s1ptasal']);

            $pens1->save(false);

            if ($post['strata'] == 'S3') {
                $pens2 = Pendidikan::findOne([
                    'orang_id' => $orangId,
                    'strata' => 2,
                ]);

                if (empty($pens2)) {
                    $pens2 = new Pendidikan([
                        'orang_id' => $orangId,
                        'strata' => 2,
                    ]);
                }


                $pens2->programStudi = $post['s2programstudi'];
                $pens2->akreditasi = $post['s2akreditasi'];
                $pens2->gelar = substr($post['s2gelar'], 0, 20);
                $pens2->jumlahSKS = $post['s2jumlahsks'];
                $pens2->ipk = $post['s2ipk'];
                $pens2->ipkAsal = $post['s2ipkasal'];
                $pens2->tahunMasuk = '';
                $pens2->tanggalLulus = $post['s2tanggallulus'];
                $pens2->noIjazah = '';
                $pens2->judulTA = $post['judul_tesis'];
                $pens2->institusi_id = $this->findInstitusiId($post['s2ptasal']);
                $pens2->save(false);
            }

            if ($post['tpa_skor'] != '') {
                $tpa = SyaratTambahan::findOne([
                    'pendaftaran_noPendaftaran' => $post['nopendaftaran'],
                    'jenisSyaratTambahan_id' => 1,
                ]);
                if (empty($tpa))
                    $tpa = new SyaratTambahan([
                        'pendaftaran_noPendaftaran' => $post['nopendaftaran'],
                        'jenisSyaratTambahan_id' => 1,
                    ]);

                $tpa->score = $post['tpa_skor'];
                $tpa->organizer = null;
                $tpa->dateExercise = $post['tpa_tanggal'];
                $tpa->dateExpired = null;

                $tpa->save(false);
            }

            if ($post['toefl_skor'] != '') {
                $tuofl = SyaratTambahan::findOne([
                    'pendaftaran_noPendaftaran' => $post['nopendaftaran'],
                    'jenisSyaratTambahan_id' => 2,
                ]);
                if (empty($tuofl))
                    $tuofl = new SyaratTambahan([
                        'pendaftaran_noPendaftaran' => $post['nopendaftaran'],
                        'jenisSyaratTambahan_id' => 2,
                    ]);

                $tuofl->score = $post['toefl_skor'];
                $tuofl->organizer = null;
                $tuofl->dateExercise = $post['toefl_tgl'];
                $tuofl->dateExpired = null;

                $tuofl->save(false);
            }


            $pendaftar = new Pendaftar([
                'noPendaftaran' => $post['nopendaftaran'],
                'orang_id' => $dataPribadi->id,
                'setujuSyarat' => 1,
                'paketPendaftaran_id' => 4,
            ]);

            $pendaftar->save();

            $verifikasi = new PinVerifikasi([
                'pin' => 'from2016',
                'dateVerifikasi' => $post['dateverifikasi'],
                'status' => 1,
                'noPendaftaran' => $pendaftar->noPendaftaran,
            ]);
            $verifikasi->save();

            $prodiId = $this->findProdiId($post['psPilihan']);
            $plihPs = new PendaftaranHasProgramStudi([
                'pendaftaran_noPendaftaran' => $pendaftar->noPendaftaran,
                'programStudi_id' => $prodiId,
                'urutan' => 1,
            ]);

            $plihPs->save();

            $sw = [1 => 4, 2 => 5, '' => ''];

            $hasilSidang = new ProsesSidang([
                'sidang_id' => 8,
                'hasilKeputusan_id' => $sw[$post['idHasilPleno']],
                'keterangan' => $post['keterangan'],
                'pendaftaran_has_programStudi_id' => $plihPs->id,
            ]);
            $hasilSidang->save();

            $final = new FinalStatus([
                'tahun' => '2016',
                'pendaftaran_noPendaftaran' => $pendaftar->noPendaftaran,
                'prosesSidang_id' => $hasilSidang->id,
                'statusMasuk_id' => 5,
            ]);

            $final->save();

            echo $post['nopendaftaran'] . $post['nama'] . ", o:$dataPribadi->id" . '<br>';
        }


        die;
    }

    public function actionFixError()
    {
        $posts = Yii::$app->db->createCommand("
        select 
                	pop.*,
                	pl2.*,
                    acc.kirimberkas,
                    acc.strata,
                    p.dateverifikasi
                    
                 from pmbpasca_2016_2017.po_pendaftaran as pop
                 inner join pmbpasca_2016_2017.pin as p on p.nopendaftaran=pop.nopendaftaran
                 inner join pmbpasca_2016_2017.or_account as acc on acc.nopendaftaran=pop.nopendaftaran
                
                 left join pmbpasca_2016_2017.tbl_pleno2 as pl2 on pl2.nopendaftaran = pop.nopendaftaran
                 
                where p.verifikasi = 1 and pl2.idHasilPleno <> 3 and pop.nopendaftaran in (
        16010297,16013031,16011943,16011146,16012561,16011819,16010510,16010512,16011390,16012251,16012431,
        16012820,16012742,16012839,16012751,16012126,16012974,16012948,16011083,16013055,16010312,16012529,
        16011893,16011624,16011675,16012833,16012672,16012466,16012527,16012297,16010711,16011703,16010703,
        16011662,16013192,16012449,16012640,16010234,16012485,16010647,16010646,16012927,16012408,16012500,
        16011550,16010311,16010506,16012544,16010910,16010979,16013074,16013070,16011706,16011524,16013112,
        16010047,16013106,16011648,16011268,16012683,16010521,16010919,16012230,16013053,16012151,16011222,
        16012169,16010673,16011135,16011566,16010104,16010502,16011825,16012497,16011653,16012563,16010854,
        16012348,16011445,16012547,16011987,16011565,16011485,16010102,16012493,16011829,16013238,16012071,
        16011265,16011978,16011977,16012866,16013118
);
        ")
            ->queryAll();


        foreach ($posts as $post) {
//            print_r($post);
//            die;
            $pendaftar = Pendaftar::findOne([
                'noPendaftaran' => $post['nopendaftaran'],
                'paketPendaftaran_id' => 4,
            ]);

            if (empty($pendaftar)) {
                echo $post['nopendaftaran'] . $post['nama'] . ", f:error" . '<br>';
                continue;
            }

            $orangId = $pendaftar->orang_id;

            $dataPribadi = DataPribadi::findOne($orangId);
            $dataPribadi->namagadisibu = $post['statuskawin'];
            $dataPribadi->gelarDepan = $post['gelardepan'];
            $dataPribadi->gelarBelakang = $post['gelarbelakang'];
            $dataPribadi->save(false);

            $orghasAlt = OrangHasAlamat::findOne([
                'orang_id' => $dataPribadi->id,
            ]);

            if (empty($orghasAlt)) {
                $alamat = new Alamat([
                    'jalan' => $post['jalan'],
                    'kodePos' => $post['kodepos'],
                    'rt' => $post['rt'],
                    'rw' => $post['rw'],
                    'desaKelurahan_kode' => $this->findKodeDesa($post['keldesa'], $post['kecamatan']),
                ]);

                $alamat->save(false);

                $orghasAlt = new OrangHasAlamat([
                    'orang_id' => $dataPribadi->id,
                    'alamat_id' => $alamat->id,
                ]);

                $orghasAlt->save(false);
            } else {
                $alamat = Alamat::findOne($orghasAlt->alamat_id);

                $alamat->jalan = $post['jalan'];
                $alamat->kodePos = $post['kodepos'];
                $alamat->rt = $post['rt'];
                $alamat->rw = $post['rw'];
                $alamat->desaKelurahan_kode = $this->findKodeDesa($post['keldesa'], $post['kecamatan']);

                $alamat->save(false);
            }

            $identitas = Identitas::findOne([
                'orang_id' => $orangId,
                'jenisIdentitas_id' => 1,
            ]);
            if (empty($identitas))
                $identitas = new Identitas([
                    'orang_id' => $orangId,
                    'jenisIdentitas_id' => 1,
                ]);

            $identitas->identitas = $post['noktp'];
            $identitas->save(false);

            $hp = Kontak::findOne([
                'orang_id' => $orangId,
                'jenisKontak_id' => 2,
            ]);
            if (empty($hp))
                $hp = new Kontak([
                    'orang_id' => $orangId,
                    'jenisKontak_id' => 2,
                ]);

            $hp->kontak = $post['mobilephone'];
            $hp->save(false);

            if ($post['phone'] != null) {
                $tlp = Kontak::findOne([
                    'orang_id' => $orangId,
                    'jenisKontak_id' => 3,
                ]);
                if (empty($tlp))
                    $tlp = new Kontak([
                        'orang_id' => $orangId,
                        'jenisKontak_id' => 3,
                    ]);
                $tlp->kontak = $post['phone'];
                $tlp->save(false);
            }

            $email = Kontak::findOne([
                'orang_id' => $orangId,
                'jenisKontak_id' => 1,
            ]);
            if (empty($email))
                $email = new Kontak([
                    'orang_id' => $orangId,
                    'jenisKontak_id' => 1,
                ]);
            $email->kontak = $post['email'];

            $email->save(false);

            if ($post['emailalternatif'] != '') {
                $ch = Kontak::find()->where([
                    'orang_id' => $orangId,
                    'jenisKontak_id' => 1,
                ])->all();
                if (sizeof($ch) < 2) {
                    $email2 = new Kontak();
                    $email2->jenisKontak_id = 1;
                    $email2->kontak = $post['emailalternatif'];
                    $email2->orang_id = $dataPribadi->id;
                    $email2->save(false);
                }
            }

            $pens1 = Pendidikan::findOne([
                'orang_id' => $orangId,
                'strata' => 1,
            ]);

            if (empty($pens1)) {
                $pens1 = new Pendidikan([
                    'orang_id' => $orangId,
                    'strata' => 1,
                ]);
            }
            $pens1->programStudi = $post['s1programstudi'];
            $pens1->akreditasi = $post['s1akreditasi'];
            $pens1->gelar = substr($post['s1gelar'], 0, 20);
            $pens1->jumlahSKS = $post['s1jumlahsks'];
            $pens1->ipk = $post['s1ipk'];
            $pens1->ipkAsal = $post['s1ipkasal'];
            $pens1->tahunMasuk = '';
            $pens1->tanggalLulus = $post['s1tanggallulus'];
            $pens1->noIjazah = '';
            $pens1->judulTA = $post['judul_skripsi'];
            $pens1->institusi_id = $this->findInstitusiId($post['s1ptasal']);

            $pens1->save(false);

            if ($post['strata'] == 'S3') {
                $pens2 = Pendidikan::findOne([
                    'orang_id' => $orangId,
                    'strata' => 2,
                ]);

                if (empty($pens2)) {
                    $pens2 = new Pendidikan([
                        'orang_id' => $orangId,
                        'strata' => 2,
                    ]);
                }


                $pens2->programStudi = $post['s2programstudi'];
                $pens2->akreditasi = $post['s2akreditasi'];
                $pens2->gelar = substr($post['s2gelar'], 0, 20);
                $pens2->jumlahSKS = $post['s2jumlahsks'];
                $pens2->ipk = $post['s2ipk'];
                $pens2->ipkAsal = $post['s2ipkasal'];
                $pens2->tahunMasuk = '';
                $pens2->tanggalLulus = $post['s2tanggallulus'];
                $pens2->noIjazah = '';
                $pens2->judulTA = $post['judul_tesis'];
                $pens2->institusi_id = $this->findInstitusiId($post['s2ptasal']);
                $pens2->save(false);
            }

            if ($post['tpa_skor'] != '') {
                $tpa = SyaratTambahan::findOne([
                    'pendaftaran_noPendaftaran' => $post['nopendaftaran'],
                    'jenisSyaratTambahan_id' => 1,
                ]);
                if (empty($tpa))
                    $tpa = new SyaratTambahan([
                        'pendaftaran_noPendaftaran' => $post['nopendaftaran'],
                        'jenisSyaratTambahan_id' => 1,
                    ]);

                $tpa->score = $post['tpa_skor'];
                $tpa->organizer = null;
                $tpa->dateExercise = $post['tpa_tanggal'];
                $tpa->dateExpired = null;

                $tpa->save(false);
            }

            if ($post['toefl_skor'] != '') {
                $tuofl = SyaratTambahan::findOne([
                    'pendaftaran_noPendaftaran' => $post['nopendaftaran'],
                    'jenisSyaratTambahan_id' => 2,
                ]);
                if (empty($tuofl))
                    $tuofl = new SyaratTambahan([
                        'pendaftaran_noPendaftaran' => $post['nopendaftaran'],
                        'jenisSyaratTambahan_id' => 2,
                    ]);

                $tuofl->score = $post['toefl_skor'];
                $tuofl->organizer = null;
                $tuofl->dateExercise = $post['toefl_tgl'];
                $tuofl->dateExpired = null;

                $tuofl->save(false);
            }
            echo $post['nopendaftaran'] . $post['nama'] . ", f:suces" . '<br>';
        }

        die;
    }

    public function findProdiId($kode)
    {
        $ps = ProgramStudi::findOne(['kode' => $kode]);
        return empty($ps) ? 0 : $ps->id;
    }

    public function findInstitusiId($name)
    {
        $pt = Institusi::find()->where(['like', "nama", $name])->one();
        if (empty($pt)) {
            $pt = new Institusi();
            $pt->nama = $name;
            $pt->save(false);
        }
        return $pt->id;
    }

    public function findKodeDesa($desa, $kec)
    {
        $des = DesaKelurahan::find()->where(['like', "namaID", $desa])->one();
        return empty($des) ? null : $des->kode;
    }
}