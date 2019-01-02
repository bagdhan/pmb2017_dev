<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/23/2017
 * Time: 10:57 AM
 */

namespace app\modules\pendaftaran\models\lengkapdata;

use app\components\Lang;
use app\models\CostumDate;
use app\modelsDB\JenisInstansi;
use app\modelsDB\Pekerjaan;
use app\modules\pendaftaran\models\Instansi;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class StatusPekerjaan
 * @package app\modules\pendaftaran\models\lengkapdata
 *
 * @property string $namaInstansi
 * @property string $tlp
 * @property string $fax
 * @property string $email
 *
 * @property string $jalan;
 * @property string $kec;
 * @property string $kab;
 * @property string $prov;
 * @property string $des;
 *
 * @property array $listJenis
 * @property Instansi $instansi
 *
 */
class StatusPekerjaan extends Pekerjaan
{

    public $status;
    public $namaInstansi;
    public $tlp;
    public $fax;
    public $email;

    // 1 = instansi, 2 = institusi
    public $inOrIt;
    public $idInOrIt;

    public $jalan;
    public $kec;
    public $kab;
    public $prov;
    public $des;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jalan', 'status', 'namaInstansi', 'jenisInstansi_id'], 'required'],
            [['jenisInstansi_id', 'orang_id'], 'integer'],
            [['jabatan'], 'string', 'max' => 100],
            [['noIdentitas'], 'string', 'max' => 50],
            [['tanggalMasuk', 'tanggalKeluar'], 'string'],
            [['tlp', 'fax', 'email', 'kec', 'kab', 'prov', 'des',], 'string'],
            [['email'],'email'],
            [['tlp', 'fax'], 'match', 'pattern' => '/^\+62[1-9]\d{7,}$/i',
                'message' => 'Format penulisan tidak sesuai, tuliskan nomor kontak telpon dengan format +62xxxxxxxxxxx 
                    (tambahkan kode-area kota Anda tanpa angka NOL). Contoh: +622518628448 
                    (+62 = kode INDONESIA; 251 = kab/kota BOGOR; 8628448 = nomor telpon'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'jenisInstansi_id' => Lang::t('Jenis Pekerjaan', 'Field of Work'),
            'status' => Yii::t('app', 'Status'),
            'jabatan' => Lang::t('Jabatan/Posisi', 'Position'),
            'tanggalMasuk' => Lang::t('Tanggal Masuk', 'Work from'),
            'noIdentitas' => Lang::t('NIP/NIK', 'ID Employee'),

            'namaInstansi' => Lang::t( 'Nama Instansi/ Tempat Bekerja', 'Name of Institution / Workplace'),


            'jalan' => Yii::t('pmb', 'Address'),
            'tlp' => Yii::t('pmb', 'Telephone Number'),
            'fax' => Yii::t('pmb', 'Fax'),
            'email' => Yii::t('pmb', 'Email'),
            'kec' => Yii::t('pmb', 'Districts'),
            'kab' => Yii::t('pmb', 'Regency / City'),
            'prov' => Yii::t('pmb', 'Province'),
            'des' => Yii::t('pmb', 'Village'),
        ];
    }

    public function __construct(array $config = [])
    {
        $iattr = new Instansi();
        $attribute = ArrayHelper::merge($this->attributes, $iattr->attributes);

        parent::__construct($config);
    }


    public function run()
    {
        $this->tanggalMasuk = $this->tanggalMasuk == null ? null : date('d-m-Y', strtotime($this->tanggalMasuk));
        $this->tanggalKeluar = $this->tanggalKeluar == null ? null : date('d-m-Y', strtotime($this->tanggalKeluar));
//        $this->inOrIt = empty($this->pekerjaanHasInstitusis) ? (empty($this->pekerjaanHasInstansis) ? null : 1) : 2;
//        $this->idInOrIt = $this->inOrIt == 2 ? $this->pekerjaanHasInstitusis[0]->institusi_id :
//            ($this->inOrIt == 1 ? $this->pekerjaanHasInstansis[0]->instansi_id : null);

        $this->getNamaInstansi();
        $this->getTlp();
        $this->getFax();
        $this->getEmail();
        $this->getDes();
        $this->getKec();
        $this->getKab();
        $this->getProv();
        $this->getJalan();
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        CostumDate::tiemZone();
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->waktuBuat = date('Y-m-d H:i:s');
                $this->waktuUbah = date('Y-m-d H:i:s');
            } else {
                $this->waktuUbah = date('Y-m-d H:i:s');
            }
            $this->tanggalMasuk = $this->tanggalMasuk == null ? null : date('Y-m-d', strtotime($this->tanggalMasuk));
            $this->tanggalKeluar = $this->tanggalKeluar == null ? null : date('Y-m-d', strtotime($this->tanggalKeluar));

            return true;
        }
        return false;
    }

    public function saveData($model)
    {
        $this->save(false);

        $instansi = Instansi::findOn($this->id);
        $instansi->load(Yii::$app->request->post(), $this->formName());
        $instansi->nama = $this->namaInstansi;
        $instansi->des = $this->des;

        return $instansi->saveData($this->id);

    }

    /**
     * @return mixed
     */
    public function getNamaInstansi()
    {
        return $this->namaInstansi = !empty($this->instansi) ? $this->instansi->nama : null;
    }

    /**
     * @return mixed
     */
    public function getTlp()
    {
        return $this->tlp = !empty($this->instansi) ? $this->instansi->tlp : null;
    }

    /**
     * @return mixed
     */
    public function getFax()
    {
        return $this->fax = !empty($this->instansi) ? $this->instansi->fax : null;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email = !empty($this->instansi) ? $this->instansi->email : null;
    }

    public function getInstansi()
    {
        return Instansi::findOn($this->id);
    }

    /**
     * @return mixed
     */
    public function getJalan()
    {
        return $this->jalan = $this->instansi->alamat->jalan;
    }

    /**
     * @param mixed $jalan
     */
    public function setJalan($jalan)
    {
        $this->jalan = $jalan;
    }

    /**
     * @return mixed
     */
    public function getKec()
    {
        return $this->kec = ($this->instansi->alamat->isNewRecord || empty($this->instansi->alamat->desaKelurahanKode)) ? null :
            $this->instansi->alamat->desaKelurahanKode->kecamatan_kode;
    }

    /**
     * @param mixed $kec
     */
    public function setKec($kec)
    {
        $this->kec = $kec;
    }

    /**
     * @return mixed
     */
    public function getKab()
    {
        return $this->kab = ($this->instansi->alamat->isNewRecord || empty($this->instansi->alamat->desaKelurahanKode)) ? null :
            $this->instansi->alamat->desaKelurahanKode->kecamatanKode->kabupatenKota_kode;
    }

    /**
     * @param mixed $kab
     */
    public function setKab($kab)
    {
        $this->kab = $kab;
    }

    /**
     * @return mixed
     */
    public function getProv()
    {
        return $this->prov = ($this->instansi->alamat->isNewRecord || empty($this->instansi->alamat->desaKelurahanKode)) ? null :
            $this->instansi->alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsi_kode;
    }

    /**
     * @param mixed $prov
     */
    public function setProv($prov)
    {
        $this->prov = $prov;
    }

    /**
     * @return mixed
     */
    public function getDes()
    {
        return $this->des = ($this->instansi->alamat->isNewRecord || empty($this->instansi->alamat->desaKelurahanKode)) ? null :
            $this->instansi->alamat->desaKelurahan_kode;
    }

    /**
     * @param mixed $des
     */
    public function setDes($des)
    {
        $this->des = $des;
    }

    /**
     * @return array
     */
    public function getListJenis()
    {
        $out = [];
        $listJenis = [
            'Belum Bekerja' => 'Unemployed',
            'Negeri' => 'Government',
            'Swasta' => 'Private',
            'Wiraswasta' => 'Entrepreneur',
            'Lainnya' => 'Other',
        ];
        foreach (JenisInstansi::find()->where('id <> 5')->all() as $idx => $item) {
            $out[$item->id] = Lang::t($item->nama, isset($listJenis[$item->nama]) ? $listJenis[$item->nama] : $item->nama );
        }
        $item = JenisInstansi::findOne(['id' => 5]);
        $out[$item->id] = Lang::t($item->nama, isset($listJenis[$item->nama]) ? $listJenis[$item->nama] : $item->nama );
        return $out;
    }
}