<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/23/2017
 * Time: 11:48 AM
 */

namespace app\modules\pendaftaran\models\lengkapdata;

use app\components\Lang;
use app\modelsDB\KerjasamaHasProgramStudi;
use app\modelsDB\ManajemenJalurMasuk;
use app\modelsDB\PaketPendaftaranHasManajemenJalurMasuk;
use app\modelsDB\PilihKerjasama;
use app\modelsDB\ProgramStudi;
use app\modules\pendaftaran\models\FormLengkapData;
use app\modules\pendaftaran\models\Pendaftaran;
use Yii;
use app\modelsDB\PendaftaranHasProgramStudi;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class PilihProdi
 * @package app\modules\pendaftaran\models\lengkapdata
 *
 * @property int $strata
 * @property int $pilihan1
 * @property int $pilihan2
 */
class PilihProdi extends DynamicModel
{

    public $strata;

    public $pilihan1;

    public $pilihan2;

    public $kerjasama;

    public static $test;

    public static $idPaket;

    public $data = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['strata', 'pilihan1'], 'required'],
            [['pilihan1'], 'exist', 'skipOnError' => true,
                'targetClass' => ProgramStudi::className(), 'targetAttribute' => ['pilihan1' => 'id']],
            [['pilihan2'], 'exist', 'skipOnError' => true,
                'targetClass' => ProgramStudi::className(), 'targetAttribute' => ['pilihan2' => 'id']],

            ['pilihan2', 'compare', 'compareAttribute' => 'pilihan1', 'operator' => '!=',
                'message' => "Pilihan Kedua tidak boleh sama dengan Pilihan Pertama"],
            [['kerjasama'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'strata' => Lang::t('Strata', 'Degree'),
            'pilihan1' => Lang::t('Pilihan Pertama', 'First Choice'),
            'pilihan2' => Lang::t('Pilihan Kedua', 'Second Choice'),
        ];
    }

    /**
     * PilihProdi constructor.
     * @param array $attributes
     * @param array $config
     */
    public function __construct(array $attributes = [], array $config = [])
    {
        $this->strata = isset($attributes['strata']) ? $attributes['strata'] : null;
        $this->pilihan1 = isset($attributes['pilihan1']) ? $attributes['pilihan1'] : null;
        $this->pilihan2 = isset($attributes['pilihan2']) ? $attributes['pilihan2'] : null;
        $idKerjasama = $this->getPdProdi1(FormLengkapData::$noPendaftaran);
        $this->setKerjasama($idKerjasama);
        $modelDaftar = FormLengkapData::$modelPendaftaran;
        self::$idPaket = empty($modelDaftar) ? 1 : $modelDaftar->paketPendaftaran_id;
        parent::__construct($attributes, $config);
    }

    /**
     * @param FormLengkapData $model
     * @return static
     */
    public static function findData($model)
    {
//        self::setStrata($model->strata);
//        self::setPilihan($model::$noPendaftaran);
        return new self([
            'strata' => $model->strata,
            'pilihan1' => self::getPilihan1($model::$noPendaftaran),
            'pilihan2' => self::getPilihan2($model::$noPendaftaran),
        ]);
    }

    /**
     * @param $noPen
     * @return bool
     * @throws NotFoundHttpException
     */
    public function save($noPen)
    {
        $this->kerjasama = isset($_POST[$this->formName()]['kerjasama']) ? $_POST[$this->formName()]['kerjasama'] : null;
        if (!$this->validate()) {
            return false;
        }

        $pendaftar = Pendaftaran::findOne(['noPendaftaran' => $noPen]);
        if (empty($pendaftar))
            throw new NotFoundHttpException('No Pendaftaran anda tidak teridentifikasi');

        $pendaftar->manajemenJalurMasuk_id = $this->getManajemenJalurMasukId();
        $pendaftar->save(false);

        $pilihan1 = PendaftaranHasProgramStudi::findOne(['pendaftaran_noPendaftaran' => $noPen, 'urutan' => 1]);
        if (empty($pilihan1)) {
            $pilihan1 = new PendaftaranHasProgramStudi();
            $pilihan1->pendaftaran_noPendaftaran = $noPen;
        }
        $pilihan1->programStudi_id = $this->pilihan1;
        $pilihan1->urutan = 1;

        if ($pilihan1->save(false)) {
            $kerjasama = PilihKerjasama::findOne(['pendaftaran_has_programStudi_id' => $pilihan1->id]);
            if ($this->kerjasama > 0) {
                if (empty($kerjasama)) {
                    $kerjasama = new PilihKerjasama();
                    $kerjasama->pendaftaran_has_programStudi_id = $pilihan1->id;
                }
                $kerjasama->kerjasama_has_programStudi_id = $this->kerjasama;
                $kerjasama->save(false);
            } else {
                if (!empty($kerjasama))
                    $kerjasama->delete();
            }

        }

        if ($this->pilihan2 > 0 || $this->pilihan2 != null) {
            $pilihan2 = PendaftaranHasProgramStudi::findOne(['pendaftaran_noPendaftaran' => $noPen, 'urutan' => 2]);
            if (empty($pilihan2)) {
                $pilihan2 = new PendaftaranHasProgramStudi();
                $pilihan2->pendaftaran_noPendaftaran = $noPen;
            }
            $pilihan2->programStudi_id = $this->pilihan2;
            $pilihan2->urutan = 2;
            $pilihan2->save(false);
        } else {
            $pilihan2 = PendaftaranHasProgramStudi::findOne(['pendaftaran_noPendaftaran' => $noPen, 'urutan' => 2]);
            if (!empty($pilihan2)) {
                $pilihan2->delete();
            }
        }

        return true;
    }

    /**
     * @return int
     */
    public function getManajemenJalurMasukId()
    {
        $listid = ArrayHelper::getColumn(PaketPendaftaranHasManajemenJalurMasuk::find()
            ->where(['paketPendaftaran_id' => self::$idPaket])
            ->asArray()
            ->all(),'manajemenJalurMasuk_id');

        $manajemen = ManajemenJalurMasuk::find()
            ->where(['programStudi_id' => $this->pilihan1])
            ->andWhere("id IN (" . join(',',$listid) . ")")
            ->one();

//        print_r($manajemen);die;
//        $query = PaketPendaftaranHasManajemenJalurMasuk::find()
//            ->innerJoinWith('manajemenJalurMasuk')
//            ->where([
//                'paketPendaftaran_has_manajemenJalurMasuk.paketPendaftaran_id' => self::$idPaket,
//                'manajemenJalurMasuk.programStudi_id' => $this->pilihan1,
//            ])->one();

        return empty($manajemen) ? null : $manajemen->id;
    }

    /**
     * @param $strata
     * @return string
     */
    public static function listProdi($strata)
    {
        $data = "<option value=''>Pilih</option>";
        $aktiveProdi = PaketPendaftaranHasManajemenJalurMasuk::find()
            ->where(['paketPendaftaran_id' => self::$idPaket])->all();

        /** @var PaketPendaftaranHasManajemenJalurMasuk $prodi */
        foreach ($aktiveProdi as $prodi) {
            if ($prodi->manajemenJalurMasuk->programStudi->strata == $strata && $prodi->manajemenJalurMasuk->aktif == 1) {
//                $data[$prodi->manajemenJalurMasuk->programStudi_id] =
//                    '(' . $prodi->manajemenJalurMasuk->programStudi->inisial . ') ' .
//                    $prodi->manajemenJalurMasuk->programStudi->nama ;
//                $data[] = [
//                    'id' => $prodi->manajemenJalurMasuk->programStudi_id,
//                    'text' => '(' . $prodi->manajemenJalurMasuk->programStudi->inisial . ') ' .
//                                $prodi->manajemenJalurMasuk->programStudi->nama .
//                                $prodi->manajemenJalurMasuk->programStudi->strata
//                ];
                $data .= "<option value='" . $prodi->manajemenJalurMasuk->programStudi_id . "'>" .
                    '(' . $prodi->manajemenJalurMasuk->programStudi->inisial . ' - S' .
                    $prodi->manajemenJalurMasuk->programStudi->strata . ') ' .
                    $prodi->manajemenJalurMasuk->programStudi->nama . "</option>";
            }
        }

        return $data;
    }

    /**
     * @param $strata
     * @return array
     */
    public static function listArrayProdi($strata)
    {
        $data = [];
        $aktiveProdi = PaketPendaftaranHasManajemenJalurMasuk::find()
            ->where(['paketPendaftaran_id' => self::$idPaket])->all();

        /** @var PaketPendaftaranHasManajemenJalurMasuk $prodi */
        foreach ($aktiveProdi as $prodi) {
            if ($prodi->manajemenJalurMasuk->programStudi->strata == $strata && $prodi->manajemenJalurMasuk->aktif == 1) {
                $data[$prodi->manajemenJalurMasuk->programStudi_id] =
                    '(' . $prodi->manajemenJalurMasuk->programStudi->inisial . ' - S' .
                    $prodi->manajemenJalurMasuk->programStudi->strata . ') ' .
                    (Lang::id() ? $prodi->manajemenJalurMasuk->programStudi->nama : $prodi->manajemenJalurMasuk->programStudi->nama_en) ;
            }
        }

        return $data;
    }

    /**
     * @param mixed $strata
     */
    public function setStrata($strata)
    {
        $this->strata = $strata;
    }

    /**
     * @param $noPen
     * @internal param mixed $pilihan
     */
    public function setPilihan($noPen)
    {
        $pilihan1 = PendaftaranHasProgramStudi::findOne(['pendaftaran_noPendaftaran' => $noPen, 'urutan' => 1]);
        $pilihan2 = PendaftaranHasProgramStudi::findOne(['pendaftaran_noPendaftaran' => $noPen, 'urutan' => 2]);
        $this->pilihan1 = empty($pilihan1) ? null : $pilihan1->programStudi_id;
        $this->pilihan2 = empty($pilihan2) ? null : $pilihan2->programStudi_id;
    }

    /**
     * @return mixed
     */
    public function getPdProdi1($noPen)
    {
        $pilihan1 = PendaftaranHasProgramStudi::findOne(['pendaftaran_noPendaftaran' => $noPen, 'urutan' => 1]);
        return empty($pilihan1->pilihKerjasamas) ? null : $pilihan1->pilihKerjasamas[0]->kerjasama_has_programStudi_id;
    }

    /**
     * @return mixed
     */
    public function getPilihan1($noPen)
    {
        $pilihan1 = PendaftaranHasProgramStudi::findOne(['pendaftaran_noPendaftaran' => $noPen, 'urutan' => 1]);
        return empty($pilihan1) ? null : $pilihan1->programStudi_id;
    }

    /**
     * @return mixed
     */
    public function getPilihan2($noPen)
    {
        $pilihan2 = PendaftaranHasProgramStudi::findOne(['pendaftaran_noPendaftaran' => $noPen, 'urutan' => 2]);
        return empty($pilihan2) ? null : $pilihan2->programStudi_id;
    }

    /**
     * @return mixed
     */
    public function getKerjasama()
    {
        return $this->kerjasama;
    }

    /**
     * @param mixed $kerjasama
     */
    public function setKerjasama($kerjasama)
    {
        $this->kerjasama = $kerjasama;
    }


}