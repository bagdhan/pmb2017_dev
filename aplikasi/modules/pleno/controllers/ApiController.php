<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 4/25/2017
 * Time: 11:38 AM
 */

namespace app\modules\pleno\controllers;

use app\models\Orang;
use app\models\ProgramStudi;
use app\modelsDB\DesaKelurahan;
use app\modelsDB\FinalStatus;
use app\modelsDB\Institusi;
use app\modelsDB\OrangHasAlamat;
use app\modelsDB\PendaftaranHasProgramStudi;
use app\modules\pendaftaran\models\Identitas;
use app\modules\pendaftaran\models\Kontak;
use app\modules\pendaftaran\models\lengkapdata\Alamat;
use app\modules\pendaftaran\models\lengkapdata\DataPribadi;
use app\modules\pendaftaran\models\lengkapdata\S1;
use app\modules\pendaftaran\models\lengkapdata\S2;
use app\modules\pendaftaran\models\PinVerifikasi;
use app\modules\pleno\models\ProsesSidang;
use app\modules\pleno\models\Sidang;
use Yii;
use app\modules\pleno\models\Pendaftar;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;

class ApiController extends Controller
{
    public function actionDetailPelamar()
    {
        $post = Yii::$app->request->post();
        if (isset($post['data'])) {
            $pelamar = Pendaftar::findOne($post['data']['nop']);
            $psidang = ProsesSidang::findOne($post['data']['idProses']);
            return $this->renderPartial('../temp/detail_person', [
                'dataPelamar' => $pelamar,
                'pSidang' => empty($psidang) ? new ProsesSidang() : $psidang,
            ]);
        }
        return '';
    }

    public function actionPutusan()
    {
        $post = Yii::$app->request->post();
        $out = [
            'message' => 'ad',
            'status' => 'no',
            'data' => '',
        ];

        if (isset($post['nop'])) {
            $noPendaftaran = $post['nop'];
            $idProses = $post['idProses'];
            $idPutusan = $post['idPutusan'];
            $findtext = $post['text'];
            $urutanPs = $post['ps'];

            $prosesSidang = ProsesSidang::findOne($idProses);
            if (empty($prosesSidang))
                $prosesSidang = new ProsesSidang();

            if ($prosesSidang->pendaftaranHasProgramStudi->pendaftaran_noPendaftaran == $noPendaftaran) {
                if (in_array($idPutusan, [6, 11]) && $urutanPs == 1) {
                    /** @var PendaftaranHasProgramStudi $pendafHProdi */
                    $pendafHProdi = PendaftaranHasProgramStudi::find()->where([
                        'pendaftaran_noPendaftaran' => $noPendaftaran,
                        'urutan' => 2,
                    ])->one();
                    if (!empty($pendafHProdi)) {
                        $pSidang = new ProsesSidang([
                            'pendaftaran_has_programStudi_id' => $pendafHProdi->id,
                            'sidang_id' => $prosesSidang->sidang_id,
                        ]);
                        $pSidang->save();
                    }
                }

                if (in_array($idPutusan, [4, 5, 9, 10]) && $urutanPs == 1) {
                    /** @var PendaftaranHasProgramStudi $pendafHProdi */
                    $pendafHProdi = PendaftaranHasProgramStudi::find()->where([
                        'pendaftaran_noPendaftaran' => $noPendaftaran,
                        'urutan' => 2,
                    ])->one();
                    if (!empty($pendafHProdi)) {
                        $pSidang = ProsesSidang::findOne([
                            'pendaftaran_has_programStudi_id' => $pendafHProdi->id,
                            'sidang_id' => $prosesSidang->sidang_id,
                        ]);
                        if (!empty($pSidang)) {
                            $prosesSidang->historyUpdate .= "{del" . Json::encode(ArrayHelper::toArray($pSidang)) . "}";
                            $pSidang->delete();
                        }
                    }
                }

                if ($idPutusan == 1) {
                    $sidangs = Sidang::findOne($prosesSidang->sidang_id);
                    foreach ($sidangs->listChild as $relasiSidang) {
                        $option = [
                            'pendaftaran_has_programStudi_id' => $prosesSidang->pendaftaran_has_programStudi_id,
                            'sidang_id' => $relasiSidang->child,
                        ];

                        $pSidang = ProsesSidang::findOne($option);
                        if (empty($pSidang))
                            $pSidang = new ProsesSidang($option);

                        $pSidang->hasilKeputusan_id = null;
                        $pSidang->keterangan = null;
                        $pSidang->save();
                    }
                }

                $prosesSidang->hasilKeputusan_id = $idPutusan;
                $prosesSidang->keterangan = $findtext;

                $out = [
                    'status' => $prosesSidang->save() ? 'yes' : 'no',
                    'message' => $prosesSidang->message,
                    'data' => $prosesSidang->actionBtn,
                ];
            }
        }
        if (isset($post['nop2'])) {
            $noPendaftaran = $post['nop2'];
            $idProses = $post['idProses'];

            $prosesSidang = ProsesSidang::findOne($idProses);
            if (empty($prosesSidang))
                $prosesSidang = new ProsesSidang();

            if ($prosesSidang->pendaftaranHasProgramStudi->pendaftaran_noPendaftaran == $noPendaftaran) {
                $prosesSidang->hasilKeputusan_id = null;
                $prosesSidang->keterangan = null;

                foreach ($prosesSidang->sidang->relasiSidangs as $relasiSidang) {
                    $option = [
                        'pendaftaran_has_programStudi_id' => $prosesSidang->pendaftaran_has_programStudi_id,
                        'sidang_id' => $relasiSidang->child,
                    ];

                    $pSidang = ProsesSidang::findOne($option);
                    if (!empty($pSidang)) {
                        $prosesSidang->historyUpdate .= "{del" . Json::encode(ArrayHelper::toArray($pSidang)) . "}";
                        $pSidang->delete();
                    }

                }

                $out = [
                    'status' => $prosesSidang->save() ? 'yes' : 'no',
                    'message' => $prosesSidang->message,
                    'data' => $prosesSidang->actionBtn,
                ];
            }
        }
        return Json::encode($out);
    }

    public function actionTest()
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
);
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
            $dataPribadi->save();

            $alamat = new Alamat([
                'jalan' => $post['jalan'],
                'kodePos' => $post['kodepos'],
                'rt' => $post['rt'],
                'rw' => $post['rw'],
                'desaKelurahan_kode' => $this->findKodeDesa($post['keldesa'], $post['kecamatan']),
            ]);

            $alamat->save();
            $orghasAlt = new OrangHasAlamat([
                'orang_id' => $dataPribadi->id,
                'alamat_id' => $alamat->id,
            ]);

            $orghasAlt->save();

            $identitas = new Identitas();
            $identitas->jenisIdentitas_id = 1;
            $identitas->identitas = $post['noktp'];
            $identitas->orang_id = $dataPribadi->id;
            $identitas->save(false);

            $hp = new Kontak();
            $hp->jenisKontak_id = 2;
            $hp->kontak = $post['mobilephone'];
            $hp->orang_id = $dataPribadi->id;
            $hp->save(false);

            if ($post['phone'] != null) {
                $tlp = new Kontak();
                $tlp->jenisKontak_id = 3;
                $tlp->kontak = $post['phone'];
                $tlp->orang_id = $dataPribadi->id;
                $tlp->save(false);
            }

            $email = new Kontak();
            $email->jenisKontak_id = 1;
            $email->kontak = $post['email'];
            $email->orang_id = $dataPribadi->id;
            $email->save(false);

            if ($post['emailalternatif'] != '') {
                $email2 = new Kontak();
                $email2->jenisKontak_id = 1;
                $email2->kontak = $post['emailalternatif'];
                $email2->orang_id = $dataPribadi->id;
                $email2->save(false);
            }

            $pens1 = new S1([
                'orang_id' => $dataPribadi->id,
                'programStudi' => $post['s1programstudi'],
                'akreditasi' => $post['s1akreditasi'],
                'gelar' => $post['s1gelar'],
                'jumlahSKS' => $post['s1jumlahsks'],
                'ipk' => $post['s1ipk'],
                'ipkAsal' => $post['s1ipkasal'],
                'tahunMasuk' => '',
                'tanggalLulus' => $post['s1tanggallulus'],
                'noIjazah' => '',
                'judulTA' => $post['judul_skripsi'],
                'institusi_id' => $this->findInstitusiId($post['s1ptasal']),
                'namaUniversitas' => $post['s1ptasal'],
            ]);
            $pens1->save();

            if ($post['strata'] == 'S3') {
                $pens2 = new S2([
                    'orang_id' => $dataPribadi->id,
                    'programStudi' => $post['s2programstudi'],
                    'akreditasi' => $post['s2akreditasi'],
                    'gelar' => $post['s2gelar'],
                    'jumlahSKS' => $post['s2jumlahsks'],
                    'ipk' => $post['s2ipk'],
                    'ipkAsal' => $post['s2ipkasal'],
                    'tahunMasuk' => '',
                    'tanggalLulus' => $post['s2tanggallulus'],
                    'noIjazah' => '',
                    'judulTA' => $post['judul_tesis'],
                    'institusi_id' => $this->findInstitusiId($post['s2ptasal']),
                    'namaUniversitas' => $post['s2ptasal'],
                ]);
                $pens2->save();
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

        $arrId = [
            16010647 => 2194, 16010919 => 2195, 16010646 => 2196, 16012466 => 2197, 16011566 => 2198,
            16011706 => 2199, 16011653 => 2200, 16011524 => 2201, 16011648 => 2202, 16011624 => 2203,
            16012297 => 2204, 16011675 => 2205, 16012485 => 2206, 16010104 => 2207, 16013192 => 2208,
            16011565 => 2209, 16010521 => 2210, 16011265 => 2211, 16011893 => 2212, 16011268 => 2213,
            16012742 => 2214, 16012251 => 2215, 16010854 => 2216, 16011445 => 2217, 16011222 => 2218,
            16012493 => 2219, 16010312 => 2220, 16011146 => 2221, 16010711 => 2222, 16012497 => 2223,
            16010102 => 2224, 16012071 => 2225, 16011485 => 2226, 16010047 => 2227, 16012839 => 2228,
            16012547 => 2229, 16011703 => 2230, 16011662 => 2231, 16010297 => 2232, 16011135 => 2233,
            16011825 => 2234, 16012563 => 2235, 16010510 => 2236, 16011550 => 2237, 16010512 => 2238,
            16012833 => 2239, 16010910 => 2240, 16010506 => 2241, 16012974 => 2242, 16010502 => 2243,
            16010673 => 2244, 16011987 => 2245, 16013031 => 2246, 16010703 => 2247, 16010311 => 2248,
            16011829 => 2249, 16013055 => 2250, 16012230 => 2251, 16012640 => 2252, 16011083 => 2253,
            16012151 => 2254, 16013112 => 2255, 16012449 => 2256, 16012527 => 2257, 16013118 => 2258,
            16012820 => 2259, 16013238 => 2260, 16012500 => 2261, 16012751 => 2262, 16011819 => 2263,
            16012948 => 2264, 16012544 => 2265, 16012126 => 2266, 16011977 => 2267, 16012866 => 2268,
            16012672 => 2269, 16012927 => 2270, 16012561 => 2271, 16012431 => 2272, 16011978 => 2273,
            16012348 => 2274, 16011390 => 2275, 16012683 => 2276, 16013053 => 2277, 16013074 => 2278,
            16013106 => 2279, 16011943 => 2280, 16010234 => 2281, 16012529 => 2282, 16012169 => 2283,
            16010979 => 2284, 16013070 => 2285, 16012408 => 2286,
        ];

        foreach ($posts as $post) {

            if (!isset($arrId[$post['nopendaftaran']])) {
                echo $post['nopendaftaran'] . $post['nama'] . ", f:error" . '<br>';
                continue;
            }

            $orangId = $arrId[$post['nopendaftaran']];
            $pendaftar = new Pendaftar([
                'noPendaftaran' => $post['nopendaftaran'],
                'orang_id' => $orangId,
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

            echo $post['nopendaftaran'] . $post['nama'] . ", f:$final->id" . '<br>';
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
        return empty($pt) ? 0 : $pt->id;
    }

    public function findKodeDesa($desa, $kec)
    {
        $des = DesaKelurahan::find()->where(['like', "namaID", $desa])->one();
        return empty($des) ? null : $des->kode;
    }
}