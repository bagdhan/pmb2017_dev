<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "sidang".
 *
 * @property int $id
 * @property string $tanggalSidang
 * @property string $deskripsi
 * @property int $kunci
 * @property int $jenisSidang_id
 * @property int $paketPendaftaran_id
 *
 * @property ProsesSidang[] $prosesSidangs
 * @property RelasiSidang[] $relasiSidangs
 * @property RelasiSidang[] $relasiSidangs0
 * @property JenisSidang $jenisSidang
 * @property PaketPendaftaran $paketPendaftaran
 * @property UserHasSidang[] $userHasSidangs
 * @property User[] $users
 */
class Sidang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sidang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tanggalSidang'], 'safe'],
            [['deskripsi'], 'string'],
            [['kunci', 'jenisSidang_id', 'paketPendaftaran_id'], 'integer'],
            [['jenisSidang_id'], 'required'],
            [['jenisSidang_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisSidang::className(), 'targetAttribute' => ['jenisSidang_id' => 'id']],
            [['paketPendaftaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaketPendaftaran::className(), 'targetAttribute' => ['paketPendaftaran_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tanggalSidang' => Yii::t('app', 'Tanggal Sidang'),
            'deskripsi' => Yii::t('app', 'Deskripsi'),
            'kunci' => Yii::t('app', 'Kunci'),
            'jenisSidang_id' => Yii::t('app', 'Jenis Sidang ID'),
            'paketPendaftaran_id' => Yii::t('app', 'Paket Pendaftaran ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProsesSidangs()
    {
        return $this->hasMany(ProsesSidang::className(), ['sidang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelasiSidangs()
    {
        return $this->hasMany(RelasiSidang::className(), ['sidang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelasiSidangs0()
    {
        return $this->hasMany(RelasiSidang::className(), ['child' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisSidang()
    {
        return $this->hasOne(JenisSidang::className(), ['id' => 'jenisSidang_id']);
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
    public function getUserHasSidangs()
    {
        return $this->hasMany(UserHasSidang::className(), ['sidang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_has_sidang', ['sidang_id' => 'id']);
    }
}
