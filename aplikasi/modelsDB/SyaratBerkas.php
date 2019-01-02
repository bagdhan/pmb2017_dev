<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "syaratBerkas".
 *
 * @property int $id
 * @property int $jenisBerkas_id
 * @property string $pendaftaran_noPendaftaran
 * @property string $file
 * @property int $verifikasi
 * @property string $dateVerifikasi
 * @property int $status
 * @property string $waktuBuat
 * @property string $waktuUbah
 *
 * @property JenisBerkas $jenisBerkas
 * @property Pendaftaran $pendaftaranNoPendaftaran
 * @property SyaratBerkasHasManajemenJalurMasuk[] $syaratBerkasHasManajemenJalurMasuks
 * @property ManajemenJalurMasuk[] $manajemenJalurMasuks
 */
class SyaratBerkas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'syaratBerkas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jenisBerkas_id', 'pendaftaran_noPendaftaran', 'waktuBuat', 'waktuUbah'], 'required'],
            [['jenisBerkas_id', 'verifikasi', 'status'], 'integer'],
            [['dateVerifikasi', 'waktuBuat', 'waktuUbah'], 'safe'],
            [['pendaftaran_noPendaftaran'], 'string', 'max' => 100],
            [['file'], 'string', 'max' => 200],
            [['jenisBerkas_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisBerkas::className(), 'targetAttribute' => ['jenisBerkas_id' => 'id']],
            [['pendaftaran_noPendaftaran'], 'exist', 'skipOnError' => true, 'targetClass' => Pendaftaran::className(), 'targetAttribute' => ['pendaftaran_noPendaftaran' => 'noPendaftaran']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'jenisBerkas_id' => Yii::t('app', 'Jenis Berkas ID'),
            'pendaftaran_noPendaftaran' => Yii::t('app', 'Pendaftaran No Pendaftaran'),
            'file' => Yii::t('app', 'File'),
            'verifikasi' => Yii::t('app', 'Verifikasi'),
            'dateVerifikasi' => Yii::t('app', 'Date Verifikasi'),
            'status' => Yii::t('app', 'Status'),
            'waktuBuat' => Yii::t('app', 'Waktu Buat'),
            'waktuUbah' => Yii::t('app', 'Waktu Ubah'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisBerkas()
    {
        return $this->hasOne(JenisBerkas::className(), ['id' => 'jenisBerkas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendaftaranNoPendaftaran()
    {
        return $this->hasOne(Pendaftaran::className(), ['noPendaftaran' => 'pendaftaran_noPendaftaran']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSyaratBerkasHasManajemenJalurMasuks()
    {
        return $this->hasMany(SyaratBerkasHasManajemenJalurMasuk::className(), ['syaratBerkas_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManajemenJalurMasuks()
    {
        return $this->hasMany(ManajemenJalurMasuk::className(), ['id' => 'manajemenJalurMasuk_id'])->viaTable('syaratBerkas_has_manajemenJalurMasuk', ['syaratBerkas_id' => 'id']);
    }
}
