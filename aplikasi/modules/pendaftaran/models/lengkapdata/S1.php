<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 2/1/2017
 * Time: 5:35 PM
 */

namespace app\modules\pendaftaran\models\lengkapdata;


use app\components\Lang;
use app\models\CostumDate;
use app\models\Orang;
use app\models\Institusi;
use Yii;

class S1 extends \app\models\Pendidikan
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $orang = Yii::$app->user->identity->orang;
        $rule = $orang->negara_id > 1 ? [
            [['orang_id', 'ipk', 'fakultas', 'programStudi', 'namaUniversitas',
                'jumlahSKS', 'tanggalLulus', 'tahunMasuk'], 'required'],
            [['orang_id', 'strata', 'jumlahSKS', 'institusi_id'], 'integer'],
            [['tanggalLulus', 'waktuBuat', 'waktuUbah'], 'safe'],
//            [['tanggalLulus',], 'date'],
            [['judulTA', 'jalan', 'des'], 'string'],
            [['fakultas','programStudi', 'negara'], 'string', 'max' => 250],
            [['akreditasi', 'ipk', 'ipkAsal'], 'string', 'max' => 5],
            [['gelar', 'tahunMasuk'], 'string', 'max' => 7],
            [['noIjazah'], 'string', 'max' => 100],
            [['institusi_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Institusi::className(), 'targetAttribute' => ['institusi_id' => 'id']],
            [['orang_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Orang::className(), 'targetAttribute' => ['orang_id' => 'id']],
            [['ipk'], 'match', 'pattern' => '/^\d*\.[0-9]{2}$/i',
                'message' => 'Format penulisan tidak sesuai'],
        ] : [
            [['orang_id', 'akreditasi', 'ipk', 'fakultas', 'programStudi', 'namaUniversitas',
                'jumlahSKS', 'tanggalLulus', 'tahunMasuk'], 'required'],
            [['orang_id', 'strata', 'jumlahSKS', 'institusi_id'], 'integer'],
            [['tanggalLulus', 'waktuBuat', 'waktuUbah'], 'safe'],
//            [['tanggalLulus',], 'date'],
            [['judulTA', 'jalan', 'des'], 'string'],
            [['fakultas','programStudi', 'negara'], 'string', 'max' => 250],
            [['akreditasi', 'ipk', 'ipkAsal'], 'string', 'max' => 5],
            [['gelar', 'tahunMasuk'], 'string', 'max' => 7],
            [['noIjazah'], 'string', 'max' => 100],
            [['institusi_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Institusi::className(), 'targetAttribute' => ['institusi_id' => 'id']],
            [['orang_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Orang::className(), 'targetAttribute' => ['orang_id' => 'id']],
            [['ipk'], 'match', 'pattern' => '/^\d*\.[0-9]{2}$/i',
                'message' => 'Format penulisan tidak sesuai'],
        ];

        return $rule;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'namaUniversitas' => Lang::t('Nama Perguruan Tinggi', 'Name of University'),
            'jalan' => Lang::t('Alamat Universitas', 'Address of University'),
            'orang_id' => Yii::t('pmb', 'Orang ID'),
            'strata' => Lang::t('Strata', 'Degree'),
            'fakultas' => Lang::t('Fakultas', 'Faculty'),
            'programStudi' => Lang::t('Program Studi', 'Study Program'),
            'akreditasi' => Yii::t('pmb', 'Akreditasi'),
            'gelar' => Yii::t('pmb', 'Degree'),
            'jumlahSKS' => Lang::t('Jumlah SKS', 'Credits'),
            'ipk' => Lang::t('IPK', 'GPA'),
            'ipkAsal' => Yii::t('pmb', 'Ipk Asal'),
            'tahunMasuk' => Lang::t('Tahun Masuk', 'Entry Year'),
            'tanggalLulus' => Lang::t('Tanggal Lulus', 'Year of Graduation'),
            'noIjazah' => Lang::t('No Ijazah', 'Diploma number'),
            'judulTA' => Lang::t('Judul Skripsi', 'Thesis Title'),
            'kec' => Yii::t('pmb', 'Districts'),
            'kab' => Yii::t('pmb', 'Regency / City'),
            'prov' => Yii::t('pmb', 'Province'),
            'des' => Yii::t('pmb', 'Village'),
            'negaraText' => Lang::t('Negara', 'Country'),
            'negara' => Lang::t('Negara', 'Country'),
        ];
    }

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

            $institusi = Institusi::findOne($this->institusi_id);
            if (empty($institusi)){
                $institusi = Institusi::findOne(['like', 'nama', $this->namaUniversitas]);
                if (empty($institusi)) {
                    $institusi = new Institusi();
                    $institusi->nama = $this->namaUniversitas;
                    $institusi->save(false);
                }
            }

            $institusi->negara_id = $this->negara;
            $institusi->save(false);

            $this->institusi_id = $institusi->id;

            $alamt = Alamat2::findOne($institusi->alamatId);
            if ($alamt == null)
                $alamt = new Alamat2();

            $alamt->jalan = $this->_jalan;
            $alamt->desaKelurahan_kode = $this->_des;
            $alamt->save(false);

            $institusi->alamatId = $alamt->id;
            $institusi->save();

            $this->tanggalLulus = $this->tanggalLulus == null ? null : date('Y-m-d H:i:s', strtotime($this->tanggalLulus));

            return true;
        }
        return false;
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        if (parent::save($runValidation, $attributeNames)) {



            return true;
        }
        return false;
    }


}