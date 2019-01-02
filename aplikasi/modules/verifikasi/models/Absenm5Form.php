<?php

namespace app\modules\verifikasi\models;

use app\models\ModelData;
use app\modelsDB\PaketVerifikasi;
use Yii;
use yii\base\Model;
use yii\helpers\Json;

/**
 * ContactForm is the model behind the contact form.
 *
 *
 */
class Absenm5Form extends Model
{
    public $inputan;

    public $noPendaftaran;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['noPendaftaran'], 'required'],
            ['noPendaftaran', 'exist', 'targetAttribute' => ['noPendaftaran' => 'noPendaftaran'],
                'targetClass' => Pendaftaran::className(), 'message' => 'Tidak Terdaftar Sebagai Pelamar'],
            //['inputan', 'string', 'min' => 2, 'max' => 255],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'inputan' => 'No Pendaftaran ',
        ];
    }

    public function addForm($params)
    {
        $tahap = PaketVerifikasi::findOne(['aktiv' => 1]);
        if (empty($tahap)) {
            Yii::$app->session->setFlash('error', "<strong>tidak ada jadwal verifikasi berkas yang aktiv.</strong>");
            return false;
        }

        $this->load($params);

        date_default_timezone_set("Asia/Jakarta");
        $daftarVerifikasi1 = Verifikasi::findAll(['pendaftaran_noPendaftaran' => $this->noPendaftaran, 'tahapVerifikasi_id' => 5]);
        $daftarVerifikasi2 = Verifikasi::findAll(['pendaftaran_noPendaftaran' => $this->noPendaftaran, 'tahapVerifikasi_id' => 6]);
        $daftarVerifikasi3 = Verifikasi::findAll(['pendaftaran_noPendaftaran' => $this->noPendaftaran,]);
        
        if (empty($daftarVerifikasi3)) {
            $msg = 'Silahkan SCAN QR CODE di Absen Verifikasi ';
            $typemsg = 'error';
        }
        elseif  (empty($daftarVerifikasi1)) {
            $msg = 'Silahkan Melakukan Tahapan di M4 Terlebih dahulu';
            $typemsg = 'error';
        } elseif ($daftarVerifikasi2 != null) {
            $msg = 'Sudah Terdaftar di M5';
            $typemsg = 'warning';
        } else {
            $daftarVerifikasi = Verifikasi::findAll(['pendaftaran_noPendaftaran' => $this->noPendaftaran, 'tahapVerifikasi_id' => 6]);

//            if ($daftarVerifikasi->prodi == null) {
//                Yii::$app->session->setFlash('error',
//                    "<strong>No pendaftaran tidak dinyatakan lolos sidang pleno.</strong>");
//                return false;
//            }

            if (empty($daftarVerifikasi)) {
                $daftarVerifikasi = new Verifikasi();
                $daftarVerifikasi->tahapVerifikasi_id = empty(Tableverifikasi::findOne(['name' => 'M5'])->id) ? 6 :
                    Tableverifikasi::findOne(['name' => 'M5'])->id;
                $daftarVerifikasi->paketVerifikasi_id = $tahap->id;
                $daftarVerifikasi->pendaftaran_noPendaftaran = $this->noPendaftaran;
                $log[1]['dateMasuk'] = date('Y-m-d H:i:s');
                $log[1]['posisi'] = 'M5';
                $daftarVerifikasi->log = Json::encode($log);
                $daftarVerifikasi->dateCreate = date('Y-m-d H:i:s');
                if ($daftarVerifikasi->save()) {
                    $msg = 'Mahasiswa berhasil masuk M5';
                    $typemsg = 'success';
                } else {
                    $msg = 'tidak dapat menginput data hubungi staf IT';
                    $typemsg = 'error';
                }
            } else {
                if ($daftarVerifikasi->paketVerifikasi->aktiv != 1) {
                    $log = Json::decode($daftarVerifikasi->log);

                    $temp['posisi'] = $daftarVerifikasi->tahapVerifikasi->name;
                    $temp['tahap'] = $daftarVerifikasi->paketVerifikasi_id;
                    $temp['noantriantahap1'] = $daftarVerifikasi->noAntrian;

                    $thp = $daftarVerifikasi->paketVerifikasi->Name;

                    $log[] = $temp;

                    $daftarVerifikasi->tahapVerifikasi_id = empty(Tableverifikasi::findOne(['name' => 'M5'])->id) ? 6 :
                        Tableverifikasi::findOne(['name' => 'M5'])->id;
                    $daftarVerifikasi->noAntrian = $daftarVerifikasi->genNoAntrian($tahap->id);
                    $daftarVerifikasi->paketVerifikasi_id = $tahap->id;

                    $temp['dateMasuk'] = date('Y-m-d H:i:s');
                    $temp['posisi'] = 'M5';
                    $temp['tahap'] = $daftarVerifikasi->paketVerifikasi_id;
                    $temp['noantriantahap1'] = $daftarVerifikasi->noAntrian;

                    $log[] = $temp;
                    $daftarVerifikasi->log = Json::encode($log);


                    if ($daftarVerifikasi->save()) {
                        $msg = 'Mahasiswa berhasil masuk M5, Mahasiswa sudah melakukan absen tahap $thp';
                        $typemsg = 'warning';
                    } else {
                        $msg = 'tidak dapat menginput data hubungi staf IT';
                        $typemsg = 'error';
                    }
                } else {
                    $meja = Tableverifikasi::findOne($daftarVerifikasi->tahapVerifikasi_id);
                    $msg = 'No pendaftaran sudah terdaftar, sekarang berada di ' . $meja->name;
                    $typemsg = 'info';
                }
            }

        }

        Yii::$app->session->setFlash($typemsg, "<strong>$msg.</strong>");

//        $view = Yii::$app->getView();
//        $view->registerJs("
//                    PNotify.prototype.options.styling = 'bootstrap3';
//                    new PNotify({
//                        title: '$typemsg',
//                        text: '$msg',
//                        type: '$typemsg'
//                    });");
        return true;
    }

    public function getDetail($noPendaftaran)
    {
        $data = Pendaftaran::findOne(['noPendaftaran' => $noPendaftaran]);
        if (empty($data))
            return '';

        $tp = "
        <div class='detailpelamar'>
            <div class='row'>
            <div class='col-md-3 col-lg-3 ' align='center'>
                <div class=\"small-box bg-aqua\">
                <div class=\"inner\">
                    <h3 style='font-size: 80px'>$data->noAntrian</h3>

                  <p>No Antrian</p>
                </div>
                <div class=\"icon\">
                    
                </div>
                
              </div>
            </div>
            
            <div class='col-md-6 col-lg-6 ' align='center'>
                <div class='panel panel-info' align='center'>
                    <table class='table table-user-information'>    
    						<tbody>
                                <tr><td>Nama</td><td> $data->name</td></tr>
                                <tr><td>Program Studi</td><td>$data->pspilihan</td></tr>
                                
                              </tbody>
    					</table>
    			</div>
            </div>
        
        </div>
        </div>";

        return $tp;
    }
}
