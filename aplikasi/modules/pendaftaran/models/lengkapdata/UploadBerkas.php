<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/23/2017
 * Time: 1:36 PM
 */

namespace app\modules\pendaftaran\models\lengkapdata;

use app\components\Lang;
use app\models\JenisBerkas;
use app\models\Orang;
use app\models\SyaratBerkas;
use app\modelsDB\JenisBerkasHasJalurMasuk;
use app\modelsDB\ManajemenJalurMasuk;
use app\modelsDB\Pekerjaan;
use app\modelsDB\PendaftaranHasProgramStudi;
use app\modules\pendaftaran\models\FormLengkapData;
use app\modules\pendaftaran\models\Pendaftaran;
use Yii;
use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Class UploadBerkas
 * @package app\modules\pendaftaran\models\lengkapdata
 */
class UploadBerkas extends DynamicModel
{
    public $urlberkas;

    private $_labels;

    private $_rules;

    private $_attribut;

    public function attributeLabels()
    {
        return $this->_labels;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return $this->_rules;
    }

    /**
     * UploadBerkas constructor.
     * @param array $attributes
     * @param array $config
     */
    public function __construct(array $attributes = [], array $config = [])
    {
        self::setBerkasAttribut();
        $attributes = ArrayHelper::merge($attributes, $this->_attribut);
        $dir = Yii::getAlias('@arsipdir') . '/' . FormLengkapData::$noPendaftaran;
        if (is_dir($dir)) {
            Yii::$app->assetManager->publish($dir);
            $this->urlberkas = Yii::$app->assetManager->getPublishedUrl($dir);
        }
        parent::__construct($attributes, $config);
    }

    public function getManajemenJalurMasukId()
    {
        $penpro = PendaftaranHasProgramStudi::findOne([
            'pendaftaran_noPendaftaran' => FormLengkapData::$noPendaftaran,
            'urutan' => 1,
        ]);

        $manajemen = ManajemenJalurMasuk::find()
            ->where(['programStudi_id' => empty($penpro) ? null : $penpro->programStudi_id])
            ->one();

        return empty($manajemen) ? null : $manajemen->id;
    }

    public function setBerkasAttribut()
    {
        $jenisBerkas = JenisBerkas::find()->where(['strata' => self::getStrata()])->orderBy('id ASC')->all();
        $output = [];
        $output['attribute'] = [];
        $pendaftar = Pendaftaran::findOne(['noPendaftaran' => FormLengkapData::$noPendaftaran]);
        if (empty($pendaftar))
            throw new NotFoundHttpException('No Pendaftaran anda tidak teridentifikasi');
        if ($pendaftar->manajemenJalurMasuk_id == null) {
            $pendaftar->manajemenJalurMasuk_id = self::getManajemenJalurMasukId();
            $pendaftar->save(false);
        }
        $orangId = isset(Yii::$app->user->identity->orang_id) ? Yii::$app->user->identity->orang_id : null;
        $orang = isset(Yii::$app->user->identity->orang_id) ? Yii::$app->user->identity->orang : new Orang();
        $statusKerja = Pekerjaan::findOne(['orang_id' => $orangId]);
        $statusKerja = empty($statusKerja) ? 0 : $statusKerja->jenisInstansi_id;
        $arrayReq = ArrayHelper::getColumn(JenisBerkasHasJalurMasuk::find()->where([
            'jalurMasuk_id' => isset($pendaftar->manajemenJalurMasuk) ? $pendaftar->manajemenJalurMasuk->jalurMasuk_id : null,
        ])->all(), 'jenisBerkas_id');

        /** @var JenisBerkas $item */
        foreach ($jenisBerkas as $item) {
            $attribute = static::syncAttribute($output['attribute'], $item->kode . '_' . $item->id);
            $berkas = SyaratBerkas::findOne([
                'pendaftaran_noPendaftaran' => FormLengkapData::$noPendaftaran,
                'jenisBerkas_id' => $item->id,
            ]);
            $value = null;
            if (!empty($berkas)) {
                $value = $berkas->file;
            } else {
                if ($orang->negara_id == 1) {
                    if (in_array($item->id, $arrayReq))
                        $output['rules'][] = [$attribute, 'required'];
                    if ($statusKerja > 1 && in_array($item->id, ['26', '11']))
                        $output['rules'][] = [$attribute, 'required', 'message' => 'Anda harus memasukan surat izin atasan.'];
                } else {
                    if (in_array($item->id, [2,3,14,15,16,17]))
                        $output['rules'][] = [$attribute, 'required'];
                }
            }
            $minsize = (200*1024);
            if (in_array($item->id, ['27','12','10', '25']))
                $minsize = null;
            if ($orang->negara_id == 1) {
                $output['attribute'][$attribute] = $value;
                $output['label'][$attribute] = Lang::id() ? $item->nama : $item->deskripsi;
                $output['rules'][] = [$attribute, 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'jpeg', 'pdf'],
                    'maxSize' => 1572864, 'minSize' => $minsize, 'tooBig' => 'ukuran file melebihi 1.5MB'];
            } else {
                if (!in_array($item->id, [1,13,26,11, 5, 18])) {
                    $output['attribute'][$attribute] = $value;
                    $output['label'][$attribute] = Lang::id() ? $item->nama : $item->deskripsi;
                    $output['rules'][] = [$attribute, 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'jpeg', 'pdf'],
                        'maxSize' => 1572864, 'minSize' => $minsize, 'tooBig' => 'ukuran file melebihi 1.5MB'];
                }
            }
        }

        $this->_attribut = $output['attribute'];
        $this->_rules = $output['rules'];
        $this->_labels = $output['label'];

        return $output;
    }

    /**
     * @param array $attributeArray
     * @param $attribute
     * @return string
     */
    public static function syncAttribute(array $attributeArray, $attribute)
    {
        if (isset($attributeArray[$attribute])) {
            if ((int)substr($attribute, -1) > 0)
                $attribute = $attribute . ((int)substr($attribute, -1) + 1);
            else
                $attribute = $attribute . (1);
        }

        return $attribute;
    }

    /**
     * @return int
     * @internal $pendaftarProdi Pendaftaran
     */
    public function getStrata()
    {
        $pendaftarProdi = PendaftaranHasProgramStudi::findOne(['pendaftaran_noPendaftaran' => FormLengkapData::$noPendaftaran]);
        return empty($pendaftarProdi) ? 2 : $pendaftarProdi->programStudi->strata;
    }

    /**
     * @param $model FormLengkapData
     * @return static
     */
    public static function loadData($model)
    {
        return new static();
    }

    public static function getBerkasAttribut($noPendaftaran)
    {
        $berkas = SyaratBerkas::find()->where(['pendaftaran_noPendaftaran' => $noPendaftaran])->orderBy('id ASC')->all();

        $output = [];
        /** @var SyaratBerkas $item */
        foreach ($berkas as $item) {
            $attribute = static::syncAttribute($output, $item->jenisBerkas->kode . '_' . $item->jenisBerkas_id);
            $output[$attribute] = $item->file;
        }
//        print_r($output);die;
        return $output;
    }


    public function saveData($model)
    {
        $dir = Yii::getAlias('@arsipdir') . '/' . $model::$noPendaftaran . '/';
        if (!file_exists(Yii::getAlias('@arsipdir'))) {
            mkdir(Yii::getAlias('@arsipdir'), 0777, true);
        }
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        foreach ($this->attributes as $attribute => $value) {
            $this->$attribute = UploadedFile::getInstance($this, $attribute);
            if ($this->$attribute != null) {
                $split = explode('_', $attribute);
                $filename = $split[0] . '_' . time() . '.' . $this->$attribute->extension;
                $this->$attribute->saveAs($dir . $filename);
                $berkas = SyaratBerkas::findOne(
                    ['jenisBerkas_id' => $split[1], 'pendaftaran_noPendaftaran' => $model::$noPendaftaran]);
                if (empty($berkas)) {
                    $berkas = new SyaratBerkas();
                    $berkas->jenisBerkas_id = $split[1];
                    $berkas->pendaftaran_noPendaftaran = $model::$noPendaftaran;
                }
                $berkas->file = $filename;
                $berkas->save(false);
            }
        }
        return true;
    }

}