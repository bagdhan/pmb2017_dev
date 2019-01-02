<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "pekerjaan".
 *
 * @property int $id
 * @property string $tanggalMasuk
 * @property string $tanggalKeluar
 * @property string $jabatan
 * @property string $noIdentitas
 * @property int $jenisInstansi_id
 * @property int $orang_id
 * @property string $pekerjaanLainnya
 * @property string $waktuBuat
 * @property string $waktuUbah
 *
 * @property JenisInstansi $jenisInstansi
 * @property Orang $orang
 * @property PekerjaanHasInstansi[] $pekerjaanHasInstansis
 * @property Instansi[] $instansis
 * @property PekerjaanHasInstitusi[] $pekerjaanHasInstitusis
 * @property Institusi[] $institusis
 */
class Pekerjaan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pekerjaan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tanggalMasuk', 'tanggalKeluar', 'waktuBuat', 'waktuUbah'], 'safe'],
            [['jenisInstansi_id', 'orang_id'], 'required'],
            [['jenisInstansi_id', 'orang_id'], 'integer'],
            [['jabatan', 'pekerjaanLainnya'], 'string', 'max' => 100],
            [['noIdentitas'], 'string', 'max' => 50],
            [['jenisInstansi_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisInstansi::className(), 'targetAttribute' => ['jenisInstansi_id' => 'id']],
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
            'tanggalMasuk' => Yii::t('app', 'Tanggal Masuk'),
            'tanggalKeluar' => Yii::t('app', 'Tanggal Keluar'),
            'jabatan' => Yii::t('app', 'Jabatan'),
            'noIdentitas' => Yii::t('app', 'No Identitas'),
            'jenisInstansi_id' => Yii::t('app', 'Jenis Instansi ID'),
            'orang_id' => Yii::t('app', 'Orang ID'),
            'pekerjaanLainnya' => Yii::t('app', 'Pekerjaan Lainnya'),
            'waktuBuat' => Yii::t('app', 'Waktu Buat'),
            'waktuUbah' => Yii::t('app', 'Waktu Ubah'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisInstansi()
    {
        return $this->hasOne(JenisInstansi::className(), ['id' => 'jenisInstansi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrang()
    {
        return $this->hasOne(Orang::className(), ['id' => 'orang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPekerjaanHasInstansis()
    {
        return $this->hasMany(PekerjaanHasInstansi::className(), ['pekerjaan_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstansis()
    {
        return $this->hasMany(Instansi::className(), ['id' => 'instansi_id'])->viaTable('pekerjaan_has_instansi', ['pekerjaan_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPekerjaanHasInstitusis()
    {
        return $this->hasMany(PekerjaanHasInstitusi::className(), ['pekerjaan_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstitusis()
    {
        return $this->hasMany(Institusi::className(), ['id' => 'institusi_id'])->viaTable('pekerjaan_has_institusi', ['pekerjaan_id' => 'id']);
    }
}
