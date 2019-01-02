<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "pendidikan".
 *
 * @property int $id
 * @property int $orang_id
 * @property int $strata 1 : S1 2 : S2 3 : S3 
 * @property string $fakultas
 * @property string $programStudi
 * @property string $akreditasi
 * @property string $gelar
 * @property int $jumlahSKS
 * @property string $ipk
 * @property string $ipkAsal
 * @property string $tahunMasuk
 * @property string $tanggalLulus
 * @property string $noIjazah
 * @property string $judulTA Judul tugas akhir
 * @property int $institusi_id
 * @property string $waktuBuat
 * @property string $waktuUbah
 *
 * @property Institusi $institusi
 * @property Orang $orang
 */
class Pendidikan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pendidikan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orang_id', 'institusi_id'], 'required'],
            [['orang_id', 'strata', 'jumlahSKS', 'institusi_id'], 'integer'],
            [['tanggalLulus', 'waktuBuat', 'waktuUbah'], 'safe'],
            [['judulTA'], 'string'],
            [['fakultas', 'programStudi'], 'string', 'max' => 250],
            [['akreditasi', 'ipk', 'ipkAsal'], 'string', 'max' => 5],
            [['gelar'], 'string', 'max' => 20],
            [['tahunMasuk'], 'string', 'max' => 7],
            [['noIjazah'], 'string', 'max' => 100],
            [['institusi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Institusi::className(), 'targetAttribute' => ['institusi_id' => 'id']],
            [['orang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orang::className(), 'targetAttribute' => ['orang_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'orang_id' => Yii::t('app', 'Orang ID'),
            'strata' => Yii::t('app', 'Strata'),
            'fakultas' => Yii::t('app', 'Fakultas'),
            'programStudi' => Yii::t('app', 'Program Studi'),
            'akreditasi' => Yii::t('app', 'Akreditasi'),
            'gelar' => Yii::t('app', 'Gelar'),
            'jumlahSKS' => Yii::t('app', 'Jumlah Sks'),
            'ipk' => Yii::t('app', 'Ipk'),
            'ipkAsal' => Yii::t('app', 'Ipk Asal'),
            'tahunMasuk' => Yii::t('app', 'Tahun Masuk'),
            'tanggalLulus' => Yii::t('app', 'Tanggal Lulus'),
            'noIjazah' => Yii::t('app', 'No Ijazah'),
            'judulTA' => Yii::t('app', 'Judul Ta'),
            'institusi_id' => Yii::t('app', 'Institusi ID'),
            'waktuBuat' => Yii::t('app', 'Waktu Buat'),
            'waktuUbah' => Yii::t('app', 'Waktu Ubah'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstitusi()
    {
        return $this->hasOne(Institusi::className(), ['id' => 'institusi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrang()
    {
        return $this->hasOne(Orang::className(), ['id' => 'orang_id']);
    }
}
