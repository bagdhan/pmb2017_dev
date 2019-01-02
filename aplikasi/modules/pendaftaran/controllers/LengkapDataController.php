<?php

namespace app\modules\pendaftaran\controllers;

use app\components\Controller;
use app\components\Lang;
use app\modelsDB\Instansi;
use app\models\Institusi;
use app\modelsDB\KerjasamaHasProgramStudi;
use app\modelsDB\Negara;
use app\modelsDB\OrangHasAlamat;
use app\modelsDB\StatusForm;
use app\modules\pendaftaran\models\FormLengkapData;
use app\modules\pendaftaran\models\Kontak;
use app\modules\pendaftaran\models\lengkapdata\Alamat;
use app\modules\pendaftaran\models\lengkapdata\Alamat2;
use app\modules\pendaftaran\models\lengkapdata\DataPribadi;
use app\modules\pendaftaran\models\lengkapdata\DynamicKontakIdentitas;
use app\modules\pendaftaran\models\lengkapdata\PerencanaanBiaya;
use app\modules\pendaftaran\models\lengkapdata\PilihProdi;
use app\modules\pendaftaran\models\lengkapdata\Publikasi;
use app\modules\pendaftaran\models\lengkapdata\Rekomendasi;
use app\modules\pendaftaran\models\lengkapdata\S1;
use app\modules\pendaftaran\models\lengkapdata\S2;
use app\modules\pendaftaran\models\lengkapdata\StatusPekerjaan;
use app\modules\pendaftaran\models\lengkapdata\SyaratTambahan;
use app\modules\pendaftaran\models\lengkapdata\UploadBerkas;
use app\modules\pendaftaran\models\ListResetUser;
use app\modules\pendaftaran\models\Pendaftaran;
use app\usermanager\models\AccessRole;
use mPDF;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\imagine\Image;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


/**
 * Class LengkapDataController
 * @package app\modules\pendaftaran\controllers
 *
 *
 */
class LengkapDataController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'update', 'create', 'delete'],
                'rules' => [
                    // allow authenticated users
                    [
                        'actions' => ['index', 'view', 'update', 'create', 'delete'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return AccessRole::getAccess($action, $rule);
                        },
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $get = Yii::$app->request->get();
        if (Yii::$app->user->isGuest)
            return Yii::$app->response->redirect(['/site/login']);

        // pemblokiran proses verifikasi pin sesuai jadwal paket pendaftaran BEGIN
        $pendaftaran = Pendaftaran::find()->where(['orang_id' => Yii::$app->user->identity->orang_id])->one();
        $paketPendaftar = $pendaftaran->paketPendaftaran;
        if (strtotime($paketPendaftar->dateEnd) < time())
            throw new NotAcceptableHttpException("Pendaftaran telah ditutup,<br> untuk lebih jelasnya  silahkan hubungi 0251 8628448. ");
        // END

        $switch = isset($get['step']) ? $get['step'] : 0;
        $model = FormLengkapData::findStep($switch);

        if ($switch == 0)
            return $this->render('redaksi', ['model' => $model]);

        $frm = 'form' . $switch;
        if (!(in_array($switch, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]) && $model::$statusForm->$frm > 0))
            throw new NotAcceptableHttpException('Harap lengkapi Form sebelumnya');

        $post = Yii::$app->request->post();

        $data = self::getData($model, $post);

        self::$data = $data;

        if (Yii::$app->request->isPost)
            self::saveData($switch, $data, $model);

        return $this->render('index', ArrayHelper::merge(self::$data, ['switch' => $switch, 'model' => $model]));
    }

    /**
     * @param FormLengkapData $model
     * @return DataPribadi|array
     * @internal param int $strata
     */
    public static function getData($model, $post)
    {
        $strata = $model->strata;

        $idOrang = Yii::$app->user->identity->orang_id;

        $datapribadi = DataPribadi::findOne($idOrang);

        if (empty($datapribadi))
            $datapribadi = new DataPribadi();
        else {
            $referen = OrangHasAlamat::findOne(['orang_id' => $idOrang]);
            if (!empty($referen))
                $alamat = $datapribadi->negara_id == 1 ? Alamat::findOne($referen->alamat_id) :
                    Alamat2::findOne($referen->alamat_id);
            $kontak = Kontak::find()->where(['orang_id' => $idOrang])->all();
//            $identitas = Identitas::find()->where(['orang_id' => $idOrang])->all();
            $pekerjaan = StatusPekerjaan::findOne(['orang_id' => $idOrang]);
            $pilihprodi = PilihProdi::findData($model);
            $dataRekom = Rekomendasi::findData($model);
            $dataSyarat = SyaratTambahan::findData($model);
            $publikasi = Publikasi::findOne(['noPendaftaran' => $model::$noPendaftaran]);

            $pendidikan['s1'] = S1::find()->where(['orang_id' => $idOrang, 'strata' => 1])->joinWith(['institusi'])->one();
            $pendidikan['s2'] = S2::find()->where(['orang_id' => $idOrang, 'strata' => 2])->joinWith(['institusi'])->one();

            $rencanabiaya = Pendaftaran::findOne(['noPendaftaran' => $model::$noPendaftaran])->perencanaanBiaya;
        }

        if (empty($alamat))
            $alamat = $datapribadi->negara_id == 1 ? new Alamat() : new Alamat2();

        if (empty($kontak))
            $kontak = new Kontak();

        if (empty($identitas))
            $identitas = new DynamicKontakIdentitas($idOrang);
//            $identitas = new Identitas();

        if (empty($pekerjaan)) {
            $pekerjaan = new StatusPekerjaan();
            $pekerjaan->orang_id = $idOrang;
        }

        if (empty($pilihprodi))
            $pilihprodi = new PilihProdi();

        if (empty($publikasi))
            $publikasi = new Publikasi();

        $syaratTambahan = new SyaratTambahan();

        $rekomendasi = new Rekomendasi();


        if (empty($pendidikan['s1']))
            $pendidikan['s1'] = new S1(['strata' => 1]);

        if (empty($pendidikan['s2']))
            $pendidikan['s2'] = new S2(['strata' => 2]);

        if (empty($rencanabiaya))
            $rencanabiaya = new PerencanaanBiaya();

        $pendidikan['s1']->orang_id = $idOrang;
        $pendidikan['s2']->orang_id = $idOrang;

//        $pekerjaan->orang_id = $idOrang;

        $alamat->init();

        $pekerjaan->run();
        $datapribadi->run();


        $upload = UploadBerkas::loadData($model);

        if (Yii::$app->request->isPost) {
            $datapribadi->load($post);
            $alamat->load($post);
            $identitas->load($post);
            $pekerjaan->load($post);
            $pendidikan['s1']->load($post);
            $pendidikan['s2']->load($post);
            $pilihprodi->load($post);
            $syaratTambahan->load($post);
            $rekomendasi->load($post);
            $publikasi->load($post);
            $rencanabiaya->load($post);
            $upload->load($post);
        }

        return [
            'datapribadi' => $datapribadi,
            'alamat' => $alamat,
            'kontak' => $kontak,
            'identitas' => $identitas,
            'pekerjaan' => $pekerjaan,
            'pendidikan' => $pendidikan,
            'pilihprodi' => $pilihprodi,
            'syaratTambahan' => $syaratTambahan,
            'dataSyarat' => $dataSyarat,
            'rekomendasi' => $rekomendasi,
            'dataRekom' => $dataRekom,
            'publikasi' => $publikasi,
            'rencanabiaya' => $rencanabiaya,
            'upload' => $upload,
        ];
    }

    public static $data;

    /**
     * @param $switch
     * @param DataPribadi $data [datapribadi]
     * @param $model FormLengkapData
     * @return bool|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public static function saveData($switch, $data, $model)
    {
        /** @var StatusForm $statusform */
        $statusform = $model::$statusForm;
        $frm = 'form' . $switch;
        if (in_array($switch, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]) && $statusform->$frm != 2) {
            $statusform->$frm = 1;
            $statusform->save(false);
        }

        if ($model->setuju == 1) {
            throw new NotFoundHttpException('Anda telah menyetujui proses kelengkapan data dan tidak dapat 
                mengubah data sampai proses penerimaan mahasiswa berakhir.');
        }

        switch ($switch) {
            case '1' :
                if ($data['datapribadi']->load(Yii::$app->request->post())) {

                    if ($data['datapribadi']->validate() && $data['datapribadi']->save(false)) {
                        $data['datapribadi']->imageFile = UploadedFile::getInstance($data['datapribadi'], 'imageFile');
                        $dir = Yii::getAlias('@arsipdir') . '/' . $model::$noPendaftaran . '/';
                        if (!file_exists(Yii::getAlias('@arsipdir'))) {
                            mkdir(Yii::getAlias('@arsipdir'), 0777, true);
                        }
                        if (!file_exists($dir)) {
                            mkdir($dir, 0777, true);
                        }
                        if ($data['datapribadi']->imageFile != null) {
                            $filename = $dir . 'foto.' . $data['datapribadi']->imageFile->extension;
                            $data['datapribadi']->imageFile->saveAs($filename);

                            //generate profile photo
                            $size = getimagesize($filename);
                            $width = $size[0];
                            $extension_pos = strrpos($filename, '.'); // find position of the last dot, so where the extension starts
                            $thumb = substr($filename, 0, $extension_pos) . '_profile.jpg';
                            Image::thumbnail($filename, $width, $width)->save($thumb, ['quality' => 50]);

                        }


                        if ($data['alamat']->load(Yii::$app->request->post()) && $data['alamat']->save(false)) {
                            $referen = OrangHasAlamat::findOne(['orang_id' => $data['datapribadi']->id]);
                            if (empty($referen)) {
                                $referen = new OrangHasAlamat();
                                $referen->orang_id = $data['datapribadi']->id;
                            }
                            $referen->alamat_id = $data['alamat']->id;
                            $referen->save(false);
                            $data['identitas']->load(Yii::$app->request->post());
                            $data['identitas']->save();
                            self::changePage($statusform, $switch);
                            return self::redirect(['/pendaftaran/lengkap-data?step=2']);
                        }
                    }
                }
                break;
            case '2' :
                if ($data['rencanabiaya']->load(Yii::$app->request->post()) && $data['rencanabiaya']->savePost($model)) {
                    self::changePage($statusform, $switch);
                    return self::redirect(['/pendaftaran/lengkap-data?step=3']);
                }
                break;
            case '3' :
                if ($data['pilihprodi']->load(Yii::$app->request->post()) && $data['pilihprodi']->save($model::$noPendaftaran)) {
                    self::changePage($statusform, $switch);
                    return self::redirect(['/pendaftaran/lengkap-data?step=4']);
                }
                break;
            case '4' :
                if ($data['pendidikan']['s1']->load(Yii::$app->request->post()) && $data['pendidikan']['s1']->save()) {
                    if ($data['pendidikan']['s2']->load(Yii::$app->request->post()) && $data['pendidikan']['s2']->save()) {
                        self::changePage($statusform, $switch);
                        return self::redirect(['/pendaftaran/lengkap-data?step=5']);
                    }
                    self::changePage($statusform, $switch);
                    return self::redirect(['/pendaftaran/lengkap-data?step=5']);
                }
                break;
            case '5' :

                if ($data['pekerjaan']->load(Yii::$app->request->post()) && $data['pekerjaan']->saveData($model)) {
                    self::changePage($statusform, $switch);
                    return self::redirect(['/pendaftaran/lengkap-data?step=6']);
                }
                break;
            case '6' :
                if ($data['syaratTambahan']->load(Yii::$app->request->post()) && $data['syaratTambahan']->saveSyarat($model::$noPendaftaran)) {
                    self::changePage($statusform, $switch);
                    return self::redirect(['/pendaftaran/lengkap-data?step=7']);
                }
                break;
            case '7' :
                if ($data['rekomendasi']->load(Yii::$app->request->post()) && $data['rekomendasi']->save($model::$noPendaftaran)) {
                    self::changePage($statusform, $switch);
                    return self::redirect(['/pendaftaran/lengkap-data?step=8']);
                }
                break;
            case '8' :

                if ($data['publikasi']->load(Yii::$app->request->post()) && $data['publikasi']->save(false)) {
                    self::changePage($statusform, $switch);
                    return self::redirect(['/pendaftaran/lengkap-data?step=9']);
                }

                break;
            case '9' :
                if ($data['upload']->load(Yii::$app->request->post()) && $data['upload']->saveData($model)) {
                    self::changePage($statusform, $switch);
                    return self::redirect(['/pendaftaran/lengkap-data?step=10']);
                }
                break;
            case '10' :
                if ($model->load(Yii::$app->request->post()) && $model->saveData()) {
                    self::changePage($statusform, $switch);
                    return self::redirect(['/pendaftaran/default/']);
                }
                break;
            default:
                throw new NotFoundHttpException('NO Page.');
        }
        self::$data = $data;
        return false;
    }


    /**
     * @param StatusForm $statusform
     * @param $page
     */
    public static function changePage($statusform, $page)
    {
        $nextPage = 'form' . ($page + 1);
        $currantPage = 'form' . $page;
        $statusform->$currantPage = 2;
        if ($statusform->$nextPage != 2)
            $statusform->$nextPage = 1;
        $statusform->save(false);
    }

    public function actionGetPartForm()
    {

    }

    public function actionApi()
    {
        if (Yii::$app->request->isAjax) {
            $get = Yii::$app->request->get();
            if (isset($get['type']) && $get['type'] == 'negara')
                return Json::encode(Negara::find()->where('id <> 1')->asArray()->all());

            if (isset($get['type']) && $get['type'] == 'kerja')
                return Json::encode(ArrayHelper::merge(
                    Instansi::find()->asArray()->all(),
                    Institusi::find()->asArray()->all()
                ));

            $idkj = isset($get['idkjs']) ? $get['idkjs'] : 0;
            if (isset($get['type']) && $get['type'] == 'kerjasama')
                return Json::encode($this->getKerjasama($get['prodi'], $idkj));

            $filter = 'a';
            if (isset($get['phrase']))
                $filter = $get['phrase'];

            $out = [];
            foreach (Institusi::find()->where("nama like '%$filter%'")->limit(10)->all() as $institusi) {
                $out[] = array_merge(ArrayHelper::toArray($institusi), [
                    'alamat_jalan' => $institusi->alamat == null ? '' : $institusi->alamat->jalan,
                ]);
            }

            return Json::encode($out);


        }
        return false;
    }


    private function getKerjasama($idProdi, $idkjs = 0)
    {
        $lists = KerjasamaHasProgramStudi::find()->where(['programStudi_id' => $idProdi])->all();
        $data = [];
        $d = 0;
        /** @var KerjasamaHasProgramStudi $list */
        foreach ($lists as $list) {
            if ($list->kerjasama->jenisKerjasama->show == 1) {
                $data[$list->kerjasama->jenisKerjasama_id]['jenis'] = $list->kerjasama->jenisKerjasama->nama;
                $data[$list->kerjasama->jenisKerjasama_id][$list->kerjasama->id] = [
                    'value' => $list->kerjasama->id,
                    'universitas' => $list->kerjasama->universitas,
                ];
                $d = 1;
            }
        }

        $link = [
            1 => Url::to(['/info/double-degree']),
            2 => Url::to(['/info/joint-degree']),
            3 => Url::to(['/info/credit-earning']),
        ];

        $li = "";
        foreach ($data as $i => $datum) {
            $td = '';
            foreach ($datum as $idx => $item) {
                $chack = $idkjs == $idx ? 'checked' : '';
                $rb = "<input type='radio' name='PilihProdi[kerjasama]' value='$idx' class='minimal' $chack />";
                if ($idx != 'jenis')
                    $td .= "<tr><td>$item[universitas]</td><td>$rb</td></tr>";
            }
            $uri = isset($link[$i]) ? $link[$i] : '';
            $li .= Html::tag('li', "$datum[jenis]
                <p><a target='_blank' href='$uri'>".Lang::t('Info lebih lanjut', 'for further information, click here')."</a> </p> 
                   <table class='table'>
                   <tr>
                        <th>". Lang::t('Universitas Mitra', 'Partner University')."</th>
                        <th>". Lang::t('Pilihan', 'Selection')."</th>
                        </tr>
                        $td
                    </table>
            ");
        }


        $data['ada'] = $d;

        $data['html'] = Html::tag('ol', $li);
        return $data;
    }

    public function actionCetak($id)
    {

        $email = Yii::$app->user->identity->email;
        $listNopen = ListResetUser::getListNopen($email);

        if (!(in_array($id, $listNopen))) {
            throw new NotAcceptableHttpException('No pendaftaran tidak valid.');
        }
        $folmulir = new FormLengkapData();

        if ($folmulir->setuju != 1)
            throw new NotAcceptableHttpException('Lengkapi data sebelum mencetak Folmulir A.');

        $data = Pendaftaran::findOne($id);

        $mpdf = new mPDF('', 'A4');

        $mpdf->WriteHTML($this->renderPartial('//../modules/admin/views/verifikasi/_reportView', array('data' => $data)));

        $mpdf->Output();

    }

}
