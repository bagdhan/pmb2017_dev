<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "manajemenJalurMasuk".
 *
 * @property int $id
 * @property int $programStudi_id
 * @property int $jalurMasuk_id
 * @property int $program_id
 * @property int $aktif
 * @property int $strata 1. S1 2. S2 3. S3 
 * @property int $biayaPendidikan_id
 *
 * @property JalurMasuk $jalurMasuk
 * @property ProgramStudi $programStudi
 * @property BiayaPendidikan $biayaPendidikan
 * @property Program $program
 * @property PaketPendaftaranHasManajemenJalurMasuk[] $paketPendaftaranHasManajemenJalurMasuks
 * @property PaketPendaftaran[] $paketPendaftarans
 * @property Pendaftaran[] $pendaftarans
 * @property SyaratBerkasHasManajemenJalurMasuk[] $syaratBerkasHasManajemenJalurMasuks
 * @property SyaratBerkas[] $syaratBerkas
 * @property SyaratTambahanHasManajemenJalurMasuk[] $syaratTambahanHasManajemenJalurMasuks
 */
class ManajemenJalurMasuk extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'manajemenJalurMasuk';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['programStudi_id', 'jalurMasuk_id', 'program_id', 'strata'], 'required'],
            [['programStudi_id', 'jalurMasuk_id', 'program_id', 'aktif', 'strata', 'biayaPendidikan_id'], 'integer'],
            [['jalurMasuk_id'], 'exist', 'skipOnError' => true, 'targetClass' => JalurMasuk::className(), 'targetAttribute' => ['jalurMasuk_id' => 'id']],
            [['programStudi_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgramStudi::className(), 'targetAttribute' => ['programStudi_id' => 'id']],
            [['biayaPendidikan_id'], 'exist', 'skipOnError' => true, 'targetClass' => BiayaPendidikan::className(), 'targetAttribute' => ['biayaPendidikan_id' => 'id']],
            [['program_id'], 'exist', 'skipOnError' => true, 'targetClass' => Program::className(), 'targetAttribute' => ['program_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'programStudi_id' => Yii::t('app', 'Program Studi ID'),
            'jalurMasuk_id' => Yii::t('app', 'Jalur Masuk ID'),
            'program_id' => Yii::t('app', 'Program ID'),
            'aktif' => Yii::t('app', 'Aktif'),
            'strata' => Yii::t('app', 'Strata'),
            'biayaPendidikan_id' => Yii::t('app', 'Biaya Pendidikan ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJalurMasuk()
    {
        return $this->hasOne(JalurMasuk::className(), ['id' => 'jalurMasuk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramStudi()
    {
        return $this->hasOne(ProgramStudi::className(), ['id' => 'programStudi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBiayaPendidikan()
    {
        return $this->hasOne(BiayaPendidikan::className(), ['id' => 'biayaPendidikan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgram()
    {
        return $this->hasOne(Program::className(), ['id' => 'program_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaketPendaftaranHasManajemenJalurMasuks()
    {
        return $this->hasMany(PaketPendaftaranHasManajemenJalurMasuk::className(), ['manajemenJalurMasuk_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaketPendaftarans()
    {
        return $this->hasMany(PaketPendaftaran::className(), ['id' => 'paketPendaftaran_id'])->viaTable('paketPendaftaran_has_manajemenJalurMasuk', ['manajemenJalurMasuk_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendaftarans()
    {
        return $this->hasMany(Pendaftaran::className(), ['manajemenJalurMasuk_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSyaratBerkasHasManajemenJalurMasuks()
    {
        return $this->hasMany(SyaratBerkasHasManajemenJalurMasuk::className(), ['manajemenJalurMasuk_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSyaratBerkas()
    {
        return $this->hasMany(SyaratBerkas::className(), ['id' => 'syaratBerkas_id'])->viaTable('syaratBerkas_has_manajemenJalurMasuk', ['manajemenJalurMasuk_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSyaratTambahanHasManajemenJalurMasuks()
    {
        return $this->hasMany(SyaratTambahanHasManajemenJalurMasuk::className(), ['manajemenJalurMasuk_id' => 'id']);
    }
}
