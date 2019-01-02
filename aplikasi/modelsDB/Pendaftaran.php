<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "pendaftaran".
 *
 * @property string $noPendaftaran
 * @property int $orang_id
 * @property int $rencanaPembiayaan_id
 * @property int $manajemenJalurMasuk_id
 * @property int $paketPendaftaran_id
 * @property int $setujuSyarat
 * @property int $verifikasiPMB
 * @property int $aktifdo
 * @property int $terimaSurat
 * @property string $waktuBuat
 * @property string $waktuUbah
 *
 * @property FinalStatus[] $finalStatuses
 * @property GenNrp[] $genNrps
 * @property KaryaIlmiah[] $karyaIlmiahs
 * @property PemberiRekomendasi[] $pemberiRekomendasis
 * @property ManajemenJalurMasuk $manajemenJalurMasuk
 * @property Orang $orang
 * @property PaketPendaftaran $paketPendaftaran
 * @property RencanaPembiayaan $rencanaPembiayaan
 * @property PendaftaranHasKerjasama[] $pendaftaranHasKerjasamas
 * @property Kerjasama[] $kerjasamas
 * @property PendaftaranHasProgramStudi[] $pendaftaranHasProgramStudis
 * @property PinVerifikasi[] $pinVerifikasis
 * @property StatusForm[] $statusForms
 * @property SyaratBerkas[] $syaratBerkas
 * @property SyaratTambahan[] $syaratTambahans
 */
class Pendaftaran extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pendaftaran';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['noPendaftaran', 'orang_id', 'paketPendaftaran_id'], 'required'],
            [['orang_id', 'rencanaPembiayaan_id', 'manajemenJalurMasuk_id', 'paketPendaftaran_id', 'setujuSyarat', 'verifikasiPMB', 'aktifdo', 'terimaSurat'], 'integer'],
            [['waktuBuat', 'waktuUbah'], 'safe'],
            [['noPendaftaran'], 'string', 'max' => 100],
            [['noPendaftaran'], 'unique'],
            [['manajemenJalurMasuk_id'], 'exist', 'skipOnError' => true, 'targetClass' => ManajemenJalurMasuk::className(), 'targetAttribute' => ['manajemenJalurMasuk_id' => 'id']],
            [['orang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orang::className(), 'targetAttribute' => ['orang_id' => 'id']],
            [['paketPendaftaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaketPendaftaran::className(), 'targetAttribute' => ['paketPendaftaran_id' => 'id']],
            [['rencanaPembiayaan_id'], 'exist', 'skipOnError' => true, 'targetClass' => RencanaPembiayaan::className(), 'targetAttribute' => ['rencanaPembiayaan_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'noPendaftaran' => Yii::t('app', 'No Pendaftaran'),
            'orang_id' => Yii::t('app', 'Orang ID'),
            'rencanaPembiayaan_id' => Yii::t('app', 'Rencana Pembiayaan ID'),
            'manajemenJalurMasuk_id' => Yii::t('app', 'Manajemen Jalur Masuk ID'),
            'paketPendaftaran_id' => Yii::t('app', 'Paket Pendaftaran ID'),
            'setujuSyarat' => Yii::t('app', 'Setuju Syarat'),
            'verifikasiPMB' => Yii::t('app', 'Verifikasi Pmb'),
            'aktifdo' => Yii::t('app', 'Aktifdo'),
            'terimaSurat' => Yii::t('app', 'Terima Surat'),
            'waktuBuat' => Yii::t('app', 'Waktu Buat'),
            'waktuUbah' => Yii::t('app', 'Waktu Ubah'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinalStatuses()
    {
        return $this->hasMany(FinalStatus::className(), ['pendaftaran_noPendaftaran' => 'noPendaftaran']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGenNrps()
    {
        return $this->hasMany(GenNrp::className(), ['noPendaftaran' => 'noPendaftaran']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKaryaIlmiahs()
    {
        return $this->hasMany(KaryaIlmiah::className(), ['noPendaftaran' => 'noPendaftaran']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPemberiRekomendasis()
    {
        return $this->hasMany(PemberiRekomendasi::className(), ['pendaftaran_noPendaftaran' => 'noPendaftaran']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManajemenJalurMasuk()
    {
        return $this->hasOne(ManajemenJalurMasuk::className(), ['id' => 'manajemenJalurMasuk_id']);
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
    public function getPaketPendaftaran()
    {
        return $this->hasOne(PaketPendaftaran::className(), ['id' => 'paketPendaftaran_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRencanaPembiayaan()
    {
        return $this->hasOne(RencanaPembiayaan::className(), ['id' => 'rencanaPembiayaan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendaftaranHasKerjasamas()
    {
        return $this->hasMany(PendaftaranHasKerjasama::className(), ['pendaftaran_noPendaftaran' => 'noPendaftaran']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKerjasamas()
    {
        return $this->hasMany(Kerjasama::className(), ['id' => 'kerjasama_id'])->viaTable('pendaftaran_has_kerjasama', ['pendaftaran_noPendaftaran' => 'noPendaftaran']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendaftaranHasProgramStudis()
    {
        return $this->hasMany(PendaftaranHasProgramStudi::className(), ['pendaftaran_noPendaftaran' => 'noPendaftaran']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPinVerifikasis()
    {
        return $this->hasMany(PinVerifikasi::className(), ['noPendaftaran' => 'noPendaftaran']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusForms()
    {
        return $this->hasMany(StatusForm::className(), ['noPendaftaran' => 'noPendaftaran']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSyaratBerkas()
    {
        return $this->hasMany(SyaratBerkas::className(), ['pendaftaran_noPendaftaran' => 'noPendaftaran']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSyaratTambahans()
    {
        return $this->hasMany(SyaratTambahan::className(), ['pendaftaran_noPendaftaran' => 'noPendaftaran']);
    }
}
