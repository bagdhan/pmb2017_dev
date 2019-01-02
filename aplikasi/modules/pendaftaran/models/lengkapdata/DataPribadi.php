<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/23/2017
 * Time: 10:50 AM
 */

namespace app\modules\pendaftaran\models\lengkapdata;

use app\components\Lang;
use app\models\Orang;
use app\modelsDB\Gelar;
use app\modelsDB\Keamanaan;
use app\modelsDB\Negara;
use app\modules\pendaftaran\models\FormLengkapData;
use Yii;

/**
 * Class DataPribadi
 * @property mixed photo
 * @package app\modules\pendaftaran\models\lengkapdata
 *
 *
 */
class DataPribadi extends Orang
{
    public $gelarDepan;
    public $gelarBelakang;

    public $kewarganegaraan;

    public $inputNegara;

    public $namagadisibu;
	
	public $tinggibadan;
	
	public $beratbadan;

    public $imageFile;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rule = $this->negara_id > 1 ? [
            ['tanggalLahir', 'cekTglLahir'],
            [['nama', 'tempatLahir', 'tanggalLahir', 'kewarganegaraan', 'jenisKelamin',
                'inputNegara', 'negara_id'], 'required'],
            [['gelarDepan', 'gelarBelakang'], 'safe'],

            [['KTP'], 'string', 'max' => 64],

        ] : [
            ['tanggalLahir', 'cekTglLahir'],
            [['nama', 'tempatLahir', 'tanggalLahir', 'kewarganegaraan', 'jenisKelamin',
                'statusPerkawinan_id', 'namagadisibu', 'agama_id', 'inputNegara', 'negara_id'], 'required'],
            [['gelarDepan', 'gelarBelakang'], 'safe'],

            [['KTP'], 'string', 'max' => 64],

        ];

        return $rule;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

            'nama' => Yii::t('pmb', 'Name'),
            'KTP' => Yii::t('pmb', 'KTP'),
            'statusPerkawinan_id' => Yii::t('pmb', 'Marital status'),
            'NPWP' => Yii::t('pmb', 'NPWP'),
            'kewarganegaraan' => Yii::t('pmb', 'Kewarganegaraan'),
            'negara_id' => Yii::t('pmb', 'Nationality'),
            'namagadisibu' => Yii::t('pmb', 'Mother\'s Maiden Name'),
			'agama_id' => Yii::t('pmb', 'Agama'),
            'imageFile' => Yii::t('pmb', 'Photo Profile'),

            'tempatLahir' => Yii::t('pmb', 'Place of Birth'),
            'tanggalLahir' => Yii::t('pmb', 'Date of Birth'),

            'jenisKelamin' => Yii::t('pmb', 'Gender'),
        ];
    }


    public function beforeSave($insert)
    {
        $gelardepan = Gelar::findOne(['orang_id' => $this->id, 'depanBelakang' => '0']);
        if (empty($gelardepan)) {
            $gelardepan = new Gelar();
            $gelardepan->depanBelakang = 0;
            $gelardepan->orang_id = $this->id;
            $gelardepan->nama = $this->gelarDepan;
            $gelardepan->urutan = 1;
            $gelardepan->save(false);
        } else {
            $gelardepan->nama = $this->gelarDepan;
            $gelardepan->urutan = 1;
            $gelardepan->save(false);
        }

        $gelarbelakang = Gelar::findOne(['orang_id' => $this->id, 'depanBelakang' => '1']);
        if (empty($gelarbelakang)) {
            $gelarbelakang = new Gelar();
            $gelarbelakang->depanBelakang = 1;
            $gelarbelakang->orang_id = $this->id;
            $gelarbelakang->nama = $this->gelarBelakang;
            $gelarbelakang->urutan = 1;
            $gelarbelakang->save(false);
        } else {
            $gelarbelakang->nama = $this->gelarBelakang;
            $gelarbelakang->urutan = 1;
            $gelarbelakang->save(false);
        }

        if ($this->namagadisibu != '') {
            $namaibu = Keamanaan::findOne(['orang_id' => $this->id]);
            if (empty($namaibu)) {
                $namaibu = new Keamanaan();
                $namaibu->orang_id = $this->id;
                $namaibu->namaGadisIbu = $this->namagadisibu;
                $namaibu->save(false);
            } else {
                $namaibu->namaGadisIbu = $this->namagadisibu;
                $namaibu->save(false);
            }
        }
		
		if ($this->tinggibadan != '') {
            $tinggibadan = Tinggibadan::findOne(['orang_id' => $this->id]);
            if (empty($tinggibadan)) {
                $tinggibadan = new Tinggibadan();
                $tinggibadan->orang_id = $this->id;
                $tinggibadan->tinggibadan = $this->tinggibadan;
                $tinggibadan->save(false);
            } else {
                $tinggibadan->tinggibadan = $this->tinggibadan;
                $tinggibadan->save(false);
            }
        }
		
		if ($this->beratbadan != '') {
            $beratbadan = Beratbadan::findOne(['orang_id' => $this->id]);
            if (empty($beratbadan)) {
                $beratbadan = new Beratbadan();
                $beratbadan->orang_id = $this->id;
                $beratbadan->beratbadan = $this->beratbadan;
                $beratbadan->save(false);
            } else {
                $beratbadan->beratbadan = $this->beratbadan;
                $beratbadan->save(false);
            }
        }

        if ($this->kewarganegaraan == 2) {
            $negara = Negara::findOne($this->negara_id);
            if (empty($negara)) {
                $negara = new Negara();
                $negara->nama = $this->inputNegara;
                $negara->save(false);
            }
            $this->negara_id = $negara->id;
        } else
            $this->negara_id = 1;

        return parent::beforeSave($insert);
    }

    /**
     *  mixed
     */
    public function setGelarDepan()
    {
        $gelar = '';
        foreach ($this->gelars as $item) {
            if ($item->depanBelakang == 0)
                $gelar .= $item->nama;
        }
        $this->gelarDepan = $gelar;
    }

    /**
     *  mixed
     */
    public function setGelarBelakang()
    {
        $gelar = '';
        foreach ($this->gelars as $item) {
            if ($item->depanBelakang == 1)
                $gelar .= $item->nama;
        }
        $this->gelarBelakang = $gelar;
    }

    /**
     * @param mixed $namagadisibu
     */
    public function setNamagadisibu()
    {
        if (!empty($this->keamanaans))
            $this->namagadisibu = $this->keamanaans[0]->namaGadisIbu;
    }
	
	public function setTinggibadan()
    {
        if (!empty($this->tinggibadans))
            $this->tinggibadan = $this->tinggibadans[0]->tinggibadan;
    }
	
	public function setBeratbadan()
    {
        if (!empty($this->beratbadans))
            $this->beratbadan = $this->beratbadans[0]->beratbadan;
    }

    public function run()
    {
        $this->setGelarBelakang();
        $this->setGelarDepan();
        $this->setNamagadisibu();
        $this->setKewarganegaraan();
        $this->setInputNegara();
        $this->tanggalLahir = $this->tanggalLahir == null ? null : date('d-m-Y', strtotime($this->tanggalLahir));
    }

    public function setKewarganegaraan()
    {
        $this->kewarganegaraan = empty($this->negara) ? 1 : ($this->negara_id == 1 ? 1 : 2);
    }

    public function setInputNegara()
    {
        $this->inputNegara = !empty($this->negara) ? $this->negara->nama : '';
    }

    public function getPhoto()
    {
        $dir = Yii::getAlias('@arsipdir') . '/' . FormLengkapData::$noPendaftaran;

        if (file_exists($dir . '/foto_profile.jpg')) {
            Yii::$app->assetManager->publish($dir);
            $directoryAsset = Yii::$app->assetManager->getPublishedUrl($dir) . '/foto_profile.jpg';
        } else {
            $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@app/adminlte/dist/') . '/img/user.jpg';
        }

        return $directoryAsset;
    }

    public function cekTglLahir($attribute, $param)
    {
        $t = Lang::t('Cek Tanggal Lahir', 'Check your Date of Birth ');
        $t = str_replace('{attribute}', $attribute, $t);
        $t = str_replace('{value}', $this->$attribute, $t);

        $tahunInput =date('Y', strtotime($this->tanggalLahir));
        $tahunSekarang = date('Y');
        if (!(abs($tahunSekarang - $tahunInput) > 15 && abs($tahunSekarang - $tahunInput ) < 90))
            $this->addError($attribute, $t);
    }


}