<?php

namespace app\controllers;

use app\modules\pendaftaran\models\Pendaftaran;
use app\modules\pendaftaran\models\PinVerifikasi;
use Yii;
use app\modelsDB\DesaKelurahan;
use app\modelsDB\KabupatenKota;
use app\modelsDB\Kecamatan;
use app\modelsDB\Departemen;
use app\modelsDB\ProgramStudi;
use yii\web\Controller;
use yii\helpers\Json;

/**
 * Class ApiController
 * @package app\controllers
 *
 *
 */
class ApiController extends \app\components\Controller
{
    public function actionIndex()
    {
        $get = Yii::$app->request->get();
        if (Yii::$app->request->isGet && !empty($get) && sizeof($get) > 1) {
            $sk = false;
            $output = [];

            if (isset($get['token']) && $get['token'] === Yii::$app->params['tokenbank']) {
                if (isset($get['np'])) {
                    $modelpin = PinVerifikasi::getPin($get['np']);
                    if (empty($modelpin)) {
                        $sk = 'Nomor Pendaftaran tidak valid';
                    } else {
                        $sk = true;
                        $output = [
                            'institusi' => 'Sekolah Pascasarjana IPB',
                            'nama' => $modelpin->noPendaftaran0->orang->nama,
                            'pin' => $modelpin->pin,
                            'noPendaftaran' => $modelpin->noPendaftaran,
                            'jumlahBayar' => '750000',
                        ];
                    }
                }
            }

            $res = [
                'data' => $output,
                'message' => $sk,
            ];
            return Json::encode($res);
        }
        return $this->render('index');
    }




    /**
     * @inheritdoc
     */
    public function actionListsWilayah()
    {
        $get = Yii::$app->request->get();

        if (isset($get['jenis'])) {

            switch ($get['jenis']) {
                case 'kab':
                    $posts = KabupatenKota::find()->where(['propinsi_kode' => $get['codeprov']])->all();
                    echo "<option >Pilih</option>";
                    /** @var KabupatenKota $post */
                    foreach($posts as $post){
                        echo "<option value='".$post->kode."'>".$post->namaID."</option>";
                    }
                    break;
                case 'kec':
                    $posts = Kecamatan::find()->where(['kabupatenKota_kode' => $get['codekab']])->all();
                    echo "<option >Pilih</option>";
                    /** @var Kecamatan $post */
                    foreach($posts as $post){
                        echo "<option value='".$post->kode."'>".$post->namaID."</option>";
                    }
                    break;
                case 'des':
                    $posts = DesaKelurahan::find()->where(['kecamatan_kode' => $get['codekec']])->all();
                    echo "<option >Pilih</option>";
                    /** @var DesaKelurahan $post */
                    foreach($posts as $post){
                        echo "<option value='".$post->kode."'>".$post->namaID."</option>";
                    }
                    break;

                default:
                    echo '';
            }
        } else
            return null;

    }

    /**
     * @inheritdoc
     */
    public function actionListsFakultas()
    {
        $get = Yii::$app->request->get();

        if (isset($get['jenis'])) {

            switch ($get['jenis']) {
                case 'dep':
                    $posts = Departemen::find()->where(['fakultas_id' => $get['fakid']])->all();
                    echo "<option >Pilih</option>";
                    /** @var KabupatenKota $post */
                    foreach($posts as $post){
                        echo "<option value='".$post->id."'>".$post->kode." - ".$post->nama."</option>";
                    }
                    break;
                case 'prodi':
                    $posts = ProgramStudi::find()->where(['departemen_id' => $get['depid']])->all();
                    echo "<option >Pilih</option>";
                    /** @var Kecamatan $post */
                    foreach($posts as $post){
                        echo "<option value='".$post->id."'>".$post->kode." - ".$post->nama."</option>";
                    }
                    break;

                default:
                    echo '';
            }
        } else
            return null;

    }

    /**
     * @inheritdoc
     */
    public function actionSendUlang($nopen)
    {
        $pendaftaran = Pendaftaran::findOne($nopen);
        return Json::encode($pendaftaran->sendEmail());
    }

    public function actionSendNopen($idOrang, $paketId)
    {
        $pendaftaran = new Pendaftaran();
        $pendaftaran->orang_id = $idOrang;
        $pendaftaran->paketPendaftaran_id = $paketId;
        $pendaftaran->generateNoPendaftaran();
        $pendaftaran->save(false);
        PinVerifikasi::getPin($pendaftaran->noPendaftaran);
        return $pendaftaran->sendEmail();
    }
}
