<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "programStudi".
 *
 * @property int $id
 * @property string $kode
 * @property string $nama berisi nama pengguna, misal Program Studi Magister Ilmu Komputer
 * @property string $nama_en
 * @property int $aktif untuk aktif jika ingin mengisi atau mengubah formulir. 1. aktif, 2. tidak aktif
 * @property int $strata 1. S1 2. S2 3. S3 
 * @property string $inisial
 * @property string $kode_nasional
 * @property string $sk_pendirian
 * @property string $mandat
 * @property string $visi_misi
 * @property int $departemen_id
 *
 * @property KerjasamaHasProgramStudi[] $kerjasamaHasProgramStudis
 * @property ManajemenJalurMasuk[] $manajemenJalurMasuks
 * @property PendaftaranHasProgramStudi[] $pendaftaranHasProgramStudis
 * @property Departemen $departemen
 * @property UserHasProgramStudi[] $userHasProgramStudis
 * @property User[] $users
 */
class ProgramStudi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'programStudi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama', 'aktif', 'strata', 'departemen_id'], 'required'],
            [['aktif', 'strata', 'departemen_id'], 'integer'],
            [['sk_pendirian', 'mandat', 'visi_misi'], 'string'],
            [['kode'], 'string', 'max' => 5],
            [['nama', 'nama_en'], 'string', 'max' => 255],
            [['inisial', 'kode_nasional'], 'string', 'max' => 10],
            [['departemen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Departemen::className(), 'targetAttribute' => ['departemen_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'kode' => Yii::t('app', 'Kode'),
            'nama' => Yii::t('app', 'Nama'),
            'nama_en' => Yii::t('app', 'Nama En'),
            'aktif' => Yii::t('app', 'Aktif'),
            'strata' => Yii::t('app', 'Strata'),
            'inisial' => Yii::t('app', 'Inisial'),
            'kode_nasional' => Yii::t('app', 'Kode Nasional'),
            'sk_pendirian' => Yii::t('app', 'Sk Pendirian'),
            'mandat' => Yii::t('app', 'Mandat'),
            'visi_misi' => Yii::t('app', 'Visi Misi'),
            'departemen_id' => Yii::t('app', 'Departemen ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKerjasamaHasProgramStudis()
    {
        return $this->hasMany(KerjasamaHasProgramStudi::className(), ['programStudi_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManajemenJalurMasuks()
    {
        return $this->hasMany(ManajemenJalurMasuk::className(), ['programStudi_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendaftaranHasProgramStudis()
    {
        return $this->hasMany(PendaftaranHasProgramStudi::className(), ['programStudi_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartemen()
    {
        return $this->hasOne(Departemen::className(), ['id' => 'departemen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasProgramStudis()
    {
        return $this->hasMany(UserHasProgramStudi::className(), ['programStudi_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_has_programStudi', ['programStudi_id' => 'id']);
    }
}
