<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "paketPendaftaran".
 *
 * @property int $id
 * @property string $uniqueUrl
 * @property string $title
 * @property string $deskripsi
 * @property string $tahun
 * @property int $active
 * @property string $dateStart
 * @property string $dateEnd
 * @property int $jalurMasuk_id
 *
 * @property Jalurmasuk $jalurMasuk
 * @property PaketPendaftaranHasManajemenJalurMasuk[] $paketPendaftaranHasManajemenJalurMasuks
 * @property ManajemenJalurMasuk[] $manajemenJalurMasuks
 * @property PaketPendaftaranHasPaketVerifikasi[] $paketPendaftaranHasPaketVerifikasis
 * @property PaketVerifikasi[] $paketVerifikasis
 * @property Pendaftaran[] $pendaftarans
 * @property Presensi[] $presensis
 * @property Sidang[] $sidangs
 * @property Surat[] $surats
 */
class PaketPendaftaran extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paketPendaftaran';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deskripsi'], 'string'],
            [['active', 'jalurMasuk_id'], 'integer'],
            [['dateStart', 'dateEnd'], 'safe'],
            [['jalurMasuk_id'], 'required'],
            [['uniqueUrl'], 'string', 'max' => 100],
            [['title'], 'string', 'max' => 250],
            [['tahun'], 'string', 'max' => 5],
            [['uniqueUrl'], 'unique'],
            [['jalurMasuk_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jalurmasuk::className(), 'targetAttribute' => ['jalurMasuk_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uniqueUrl' => Yii::t('app', 'Unique Url'),
            'title' => Yii::t('app', 'Title'),
            'deskripsi' => Yii::t('app', 'Deskripsi'),
            'tahun' => Yii::t('app', 'Tahun'),
            'active' => Yii::t('app', 'Active'),
            'dateStart' => Yii::t('app', 'Date Start'),
            'dateEnd' => Yii::t('app', 'Date End'),
            'jalurMasuk_id' => Yii::t('app', 'Jalur Masuk ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJalurMasuk()
    {
        return $this->hasOne(Jalurmasuk::className(), ['id' => 'jalurMasuk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaketPendaftaranHasManajemenJalurMasuks()
    {
        return $this->hasMany(PaketPendaftaranHasManajemenJalurMasuk::className(), ['paketPendaftaran_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManajemenJalurMasuks()
    {
        return $this->hasMany(ManajemenJalurMasuk::className(), ['id' => 'manajemenJalurMasuk_id'])->viaTable('paketPendaftaran_has_manajemenJalurMasuk', ['paketPendaftaran_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaketPendaftaranHasPaketVerifikasis()
    {
        return $this->hasMany(PaketPendaftaranHasPaketVerifikasi::className(), ['paketPendaftaran_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaketVerifikasis()
    {
        return $this->hasMany(PaketVerifikasi::className(), ['id' => 'paketVerifikasi_id'])->viaTable('paketPendaftaran_has_paketVerifikasi', ['paketPendaftaran_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendaftarans()
    {
        return $this->hasMany(Pendaftaran::className(), ['paketPendaftaran_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPresensis()
    {
        return $this->hasMany(Presensi::className(), ['paketPendaftaran_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSidangs()
    {
        return $this->hasMany(Sidang::className(), ['paketPendaftaran_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurats()
    {
        return $this->hasMany(Surat::className(), ['paketPendaftaran_id' => 'id']);
    }
}
