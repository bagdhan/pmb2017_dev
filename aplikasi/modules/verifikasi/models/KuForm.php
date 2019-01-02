<?php

namespace app\modules\verifikasi\models;

use app\modules\admin\models\GenNrp;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class KuForm extends Model
{
    public $inputan;

    public $noPendaftaran;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['inputan'], 'required'],
            ['noPendaftaran', 'exist',
                'targetClass' => '\app\modules\pleno\models\Pleno2', 'message' => 'tidak terdaftar.'],
            //['inputan', 'string', 'min' => 2, 'max' => 255],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'inputan' => 'No Pendaftaran / NRP',
        ];
    }

    public function addForm($params)
    {
        $this->load($params);



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

        date_default_timezone_set("Asia/Jakarta");

        if (!$this->validate()) {
            $msg = 'tidak terdaftar';
            $typemsg = 'error';
        } else {
            $daftarabsen = Absensi::findOne(['identity' => $this->noPendaftaran, 'even' => 'KuliahUmum']);

            if (empty($daftarabsen)) {
                $daftarabsen = new Absensi();

                $daftarabsen->identity = $this->noPendaftaran;
                $daftarabsen->even = "KuliahUmum";

                if ($daftarabsen->save()) {
                    $msg = 'Mahasiswa berhasil daftar';
                    $typemsg = 'success';
                } else {
                    $msg = 'tidak dapat menginput data hubungi staf IT';
                    $typemsg = 'error';
                }

            } else {
                $msg = 'Mahasiswa telah terdaftar';
                $typemsg = 'info';
            }

        }

        $view = Yii::$app->getView();
        $view->registerJs("
                    PNotify.prototype.options.styling = 'bootstrap3';
                    new PNotify({
                        title: '$typemsg',
                        text: '$msg',
                        type: '$typemsg'
                    });");
        return true;
    }

    public function getDetail()
    {
        $data = Absensi::getOneData($this->noPendaftaran);
        //print_r($this->noPendaftaran);
        if ($data->nama == '')
            return '';
        $nopendaftaran = $this->noPendaftaran;

        $dirpen = Yii::getAlias('@arsip') . "/" . $nopendaftaran . "/";
        $dirpen2 = Yii::getAlias('@arsipdir') . "/" . $nopendaftaran . "/";
        $doc = [];
        if (is_dir($dirpen2)) {
            if ($dir = opendir($dirpen2)) {
                while (($file = readdir($dir)) !== false) {
                    if ($file != "." && $file != "..") {
                        $exp1 = explode('.', $file);
                        if ($exp1[0] == 'pasfoto' && ($exp1[1] == 'jpg' || $exp1[1] == 'png'))
                            $doc[$exp1[0]] = $file;
                        $doc[$exp1[0]] = $file;
                    }
                }
            }
        }

        $foto = isset($doc['pasfoto']) ? ($dirpen . $doc['pasfoto']) :
            (Yii::getAlias('@web') . '/img/noprofilepic.jpg');

        $tp = "
        <div class='detailpelamar'>
            <div class='row'>
            <div class='col-md-3 col-lg-3 ' align='center'>
                <div class=\"small-box bg-aqua\">
                
                
              </div>
            </div>
            
            <div class='col-md-6 col-lg-6 ' align='center'>
                <div class='panel panel-info' align='center'>
                    <table class='table table-user-information'>    
    						<tbody>
                                <tr><td>Nama</td><td> $data->nama</td></tr>
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
