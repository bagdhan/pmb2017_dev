<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "kerjasama".
 *
 * @property int $id
 * @property string $universitas
 * @property int $jenisKerjasama_id
 * @property string $kerjasamacol
 *
 * @property JenisKerjasama $jenisKerjasama
 * @property KerjasamaHasProgramStudi[] $kerjasamaHasProgramStudis
 * @property PendaftaranHasKerjasama[] $pendaftaranHasKerjasamas
 * @property Pendaftaran[] $pendaftaranNoPendaftarans
 */
class Kerjasama extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kerjasama';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'jenisKerjasama_id'], 'required'],
            [['id', 'jenisKerjasama_id'], 'integer'],
            [['universitas'], 'string', 'max' => 250],
            [['kerjasamacol'], 'string', 'max' => 45],
            [['jenisKerjasama_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisKerjasama::className(), 'targetAttribute' => ['jenisKerjasama_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'universitas' => Yii::t('app', 'Universitas'),
            'jenisKerjasama_id' => Yii::t('app', 'Jenis Kerjasama ID'),
            'kerjasamacol' => Yii::t('app', 'Kerjasamacol'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisKerjasama()
    {
        return $this->hasOne(JenisKerjasama::className(), ['id' => 'jenisKerjasama_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKerjasamaHasProgramStudis()
    {
        return $this->hasMany(KerjasamaHasProgramStudi::className(), ['kerjasama_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendaftaranHasKerjasamas()
    {
        return $this->hasMany(PendaftaranHasKerjasama::className(), ['kerjasama_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendaftaranNoPendaftarans()
    {
        return $this->hasMany(Pendaftaran::className(), ['noPendaftaran' => 'pendaftaran_noPendaftaran'])->viaTable('pendaftaran_has_kerjasama', ['kerjasama_id' => 'id']);
    }
}
