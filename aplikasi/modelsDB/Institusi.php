<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "institusi".
 *
 * @property int $id
 * @property string $nama
 * @property string $kodePT
 * @property string $tlp
 * @property string $fax
 * @property string $email
 * @property string $website
 * @property string $tanggalBerdiri
 * @property string $nomorSKPT
 * @property string $tanggalSKPT
 * @property int $status
 * @property int $negara_id
 * @property int $jenisInstitusi_id
 *
 * @property JenisInstitusi $jenisInstitusi
 * @property Negara $negara
 * @property InstitusiHasAlamat[] $institusiHasAlamats
 * @property Alamat[] $alamats
 * @property PekerjaanHasInstitusi[] $pekerjaanHasInstitusis
 * @property Pekerjaan[] $pekerjaans
 * @property Pendidikan[] $pendidikans
 */
class Institusi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'institusi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tanggalBerdiri', 'tanggalSKPT'], 'safe'],
            [['status', 'negara_id', 'jenisInstitusi_id'], 'integer'],
            [['nama'], 'string', 'max' => 250],
            [['kodePT'], 'string', 'max' => 15],
            [['tlp', 'nomorSKPT'], 'string', 'max' => 45],
            [['fax', 'website'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 100],
            [['jenisInstitusi_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisInstitusi::className(), 'targetAttribute' => ['jenisInstitusi_id' => 'id']],
            [['negara_id'], 'exist', 'skipOnError' => true, 'targetClass' => Negara::className(), 'targetAttribute' => ['negara_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nama' => Yii::t('app', 'Nama'),
            'kodePT' => Yii::t('app', 'Kode Pt'),
            'tlp' => Yii::t('app', 'Tlp'),
            'fax' => Yii::t('app', 'Fax'),
            'email' => Yii::t('app', 'Email'),
            'website' => Yii::t('app', 'Website'),
            'tanggalBerdiri' => Yii::t('app', 'Tanggal Berdiri'),
            'nomorSKPT' => Yii::t('app', 'Nomor Skpt'),
            'tanggalSKPT' => Yii::t('app', 'Tanggal Skpt'),
            'status' => Yii::t('app', 'Status'),
            'negara_id' => Yii::t('app', 'Negara ID'),
            'jenisInstitusi_id' => Yii::t('app', 'Jenis Institusi ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisInstitusi()
    {
        return $this->hasOne(JenisInstitusi::className(), ['id' => 'jenisInstitusi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNegara()
    {
        return $this->hasOne(Negara::className(), ['id' => 'negara_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstitusiHasAlamats()
    {
        return $this->hasMany(InstitusiHasAlamat::className(), ['institusi_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlamats()
    {
        return $this->hasMany(Alamat::className(), ['id' => 'alamat_id'])->viaTable('institusi_has_alamat', ['institusi_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPekerjaanHasInstitusis()
    {
        return $this->hasMany(PekerjaanHasInstitusi::className(), ['institusi_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPekerjaans()
    {
        return $this->hasMany(Pekerjaan::className(), ['id' => 'pekerjaan_id'])->viaTable('pekerjaan_has_institusi', ['institusi_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendidikans()
    {
        return $this->hasMany(Pendidikan::className(), ['institusi_id' => 'id']);
    }
}
