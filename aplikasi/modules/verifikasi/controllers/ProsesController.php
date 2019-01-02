<?php

namespace app\modules\verifikasi\controllers;

//use app\modules\rekap\rekap;
use app\modules\verifikasi\models\ListBeasiswa;
use app\modules\verifikasi\models\Pendaftaran;
use app\modules\verifikasi\models\Tableverifikasi;
use app\modules\verifikasi\models\Verifikasi;
use app\modules\verifikasi\models\VerifikasiBeasiswa;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;

/**
 * Class ProsesController
 * @package app\modules\verifikasi\controllers
 */
class ProsesController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index',],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    public function actionIndex($meja)
    {
        $data['title'] = "Daftar Mahasiswa $meja";
        $idmja = Tableverifikasi::findOne(['name' => $meja]);
        if (empty($idmja))
            throw new NotAcceptableHttpException('Meja Belum terdaftar');
        $data['tabelurl'] = Url::to(['/verifikasi/proses/api', 'getdatatable' => '1', 'm' => $meja]);
        $data['button'] = Html::button("Lanjut Ke " . $idmja->name, ['class' => 'btn btn-sm btn-success', 'data-action' => 'status']) .
            Html::button("Meja Khusus", ['class' => 'btn btn-sm btn-warning', 'data-action' => 'status']) .
            Html::button("Tolak", ['class' => 'btn btn-sm btn-danger', 'data-action' => 'status']);

        //$data['nextm'] = "M0";
        if (Tableverifikasi::find()->where('urutanMeja <> 0')->max('urutanMeja') == $idmja->urutanMeja) {
            $data['nextm'] = 'Selesai';
        } elseif (Tableverifikasi::find()->where('urutanMeja <> 0')->min('urutanMeja') == $idmja->urutanMeja) {
            $data['nextm'] = 'M2';
        } elseif (0 == (int)$idmja->urutanMeja) {
            $data['nextm'] = 'M0';
        } elseif (Tableverifikasi::find()->where('urutanMeja <> 0')->min('urutanMeja') < $idmja->urutanMeja) {
            $nmeja = Tableverifikasi::findOne(['urutanMeja' => ((int)$idmja->urutanMeja + 1)]);
            $data['nextm'] = $nmeja->name;
        }
        return $this->render('index', ['data' => $data]);
    }

    public function actionMeja0()
    {

    }

    public function actionDetail()
    {
        $noPendaftaran = isset($_POST['data']['nop']) ? $_POST['data']['nop'] : '0';

        $data = Pendaftaran::findOne(['noPendaftaran' => $noPendaftaran]);
        if (empty($data))
            return '';

        $beaVerifikasi = VerifikasiBeasiswa::findOne(['pendaftaran_noPendaftaran' => $noPendaftaran]);
        if (empty($beaVerifikasi)) {
            $beaVerifikasi = new VerifikasiBeasiswa(['pendaftaran_noPendaftaran' => $noPendaftaran]);
            $beaVerifikasi->listBeasiswa_id = 1;
            $beaVerifikasi->save(false);
        }

        $drop = Html::dropDownList('pilihanbeasiswa', $beaVerifikasi->listBeasiswa_id,
            ArrayHelper::map(ListBeasiswa::find()->all(), 'id', 'namaBeasiswa'), [
                'data-action' => 'statusbeasiswa',
                    'class' => 'form-control'
            ]) . Html::input('hidden', 'nopen', $noPendaftaran);

        $load = "<div class='col-lg-6'>$drop</div><div class='col-lg-6 loadingchange'>
                    <i class='fa fa-spinner fa-spin' style='display: none; '></i></div>";

        $tp = "
        <div class='detailpelamar'>
            <div class='row'>
            <div class='col-md-3 col-lg-3 ' align='center'>
                <div class=\"small-box bg-aqua\">
                <img width='200px' src='$data->photo'>
                
              </div>
            </div>
            
            <div class='col-md-6 col-lg-6 ' align='center'>
                <div class='panel panel-info' align='center'>
                    <table class='table table-user-information'>    
    						<tbody>
                                <tr><td>Nama</td><td> $data->name</td></tr>
                                <tr><td>Strata</td><td> $data->strata</td></tr>
                                <tr><td>Program Studi</td><td>$data->pspilihan</td></tr>
                                <tr><td>Sumber Biaya</td><td> $load</td></tr>
                              </tbody>
    					</table>
    			</div>
            </div>
        
        </div>
        </div>";

        return $tp;
    }

    public function actionApi()
    {
        date_default_timezone_set("Asia/Jakarta");
        if (Yii::$app->request->isAjax) {

            if (isset($_GET['getdatatable'])) {
                $meja = isset($_GET['m']) ? $_GET['m'] : 'M0';
                $data = $this->getMahasiswaMeja($meja);
                return Json::encode(['data' => $data]);
            }

            if (isset($_POST['setproses'])) {
                $data = $this->setProses();
                return Json::encode(['update' => $data]);
            }
        } else
            print_r($this->setProses($_GET['m']));
    }

    public function getMahasiswaMeja($m)
    {
        $idmja = Tableverifikasi::findOne(['name' => $m]);
        $verifikasi = Verifikasi::getData($idmja->id, " and idTableVerifikasi = '$idmja->id'");

        return $verifikasi;
    }

    public function setProses()
    {
        $noPendaftaran = $_POST['nop'];
        $tujuan = $_POST['mtujuan'];
        $nmeja = Tableverifikasi::findOne(['name' => $tujuan]);

        $daftarVerifikasi = Verifikasi::findOne(['pendaftaran_noPendaftaran' => $noPendaftaran]);
        $m5 = $daftarVerifikasi->tahapVerifikasi_id;
        $daftarVerifikasi->tahapVerifikasi_id = empty($nmeja) ? 0 : $nmeja->id;

        if ($tujuan == 'Selesai') {
            $daftarVerifikasi->tahapVerifikasi_id = $m5;
            $daftarVerifikasi->selesai = 1;
        }

        $log = Json::decode($daftarVerifikasi->log);

        $temp['dateMasuk'] = date('Y-m-d H:i:s');
        $temp['posisi'] = $tujuan;

        $log[] = $temp;

        $daftarVerifikasi->log = Json::encode($log);
        if ($daftarVerifikasi->save()) {
            return true;
        } else {
            return false;
        }


    }

    public function actionSetBeasiswa()
    {
        $noPendaftaran = $_POST['nop'];
        $idbea = $_POST['idbea'];
        $ket = $_POST['ket'];

        $beaVerifikasi = VerifikasiBeasiswa::findOne(['pendaftaran_noPendaftaran' => $noPendaftaran]);
        if (empty($beaVerifikasi))
            $beaVerifikasi = new VerifikasiBeasiswa(['pendaftaran_noPendaftaran' => $noPendaftaran]);

        $beaVerifikasi->listBeasiswa_id = $idbea;
        $beaVerifikasi->ket = $ket;

        if ($beaVerifikasi->save()) {
            return Json::encode([
                'status' => true,
            ]);
        } else {
            return Json::encode([
                'status' => false,
            ]);
        }
    }

    public function actionSetprosesm()
    {
        $noPendaftaran = $_GET['nop'];
        $tujuan = $_GET['mtujuan'];
        $nmeja = Tableverifikasi::findOne(['name' => $tujuan]);

        $daftarVerifikasi = Verifikasi::findOne(['pendaftaran_noPendaftaran' => $noPendaftaran]);

        if (empty($daftarVerifikasi))
            die('no pendaftaran tidak valid');

        $m5 = $daftarVerifikasi->tahapVerifikasi_id;
        $daftarVerifikasi->tahapVerifikasi_id = empty($nmeja) ? 0 : $nmeja->id;

        if ($tujuan == 'Selesai') {
            $daftarVerifikasi->tahapVerifikasi_id = $m5;
            $daftarVerifikasi->selesai = 1;
        }

        $log = Json::decode($daftarVerifikasi->log);

        $temp['dateMasuk'] = date('Y-m-d H:i:s');
        $temp['posisi'] = $tujuan;
        $temp['mobile'] = 'link-mobile';

        $log[] = $temp;

        $daftarVerifikasi->log = Json::encode($log);
        if ($daftarVerifikasi->save()) {
            print_r('ok');
            die();
        } else {
            print_r('no');
            die();
        }

    }

    public function actionLog()
    {
        /** @var Verifikasi[] $data */
        $data = Verifikasi::find()->all();
        $tbl = "<table class='table' id='tabellog'>
                <thead>
                    <tr>
                        <th>Nopen</th>
                        <th>Nama</th>
                        <th>tahap/antrian</th>
                        <th>meja</th>
                        <th>log</th>
                    </tr></thead><tbody>";
        foreach ($data as $item) {
            $tbl .= "<tr>
                        <td>$item->noPendaftaran</td>
                        <td>$item->name</td>
                        <td>$item->tahap</td>
                        <td>$item->meja</td>
                        <td>$item->logActivity</td>
                    </tr>";
        }
        $tbl .= "</tbody><tfoot>
                    <tr>
                        <th>Nopen</th>
                        <th>Nama</th>
                        <th>tahap/antrian</th>
                        <th>meja</th>
                        <th>log</th>
                    </tr></tfoot></table>";
        return $this->render('rekap', ['table' => $tbl]);
    }

}
