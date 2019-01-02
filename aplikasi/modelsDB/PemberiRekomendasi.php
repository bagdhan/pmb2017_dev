<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "pemberiRekomendasi".
 *
 * @property int $id
 * @property string $nama
 * @property string $jabatan
 * @property string $deskripsi
 * @property string $pendaftaran_noPendaftaran
 *
 * @property Pendaftaran $pendaftaranNoPendaftaran
 */
class PemberiRekomendasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pemberiRekomendasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deskripsi'], 'string'],
            [['pendaftaran_noPendaftaran'], 'required'],
            [['nama'], 'string', 'max' => 200],
            [['jabatan', 'pendaftaran_noPendaftaran'], 'string', 'max' => 100],
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
            'nama' => Yii::t('app', 'Nama'),
            'jabatan' => Yii::t('app', 'Jabatan'),
            'deskripsi' => Yii::t('app', 'Deskripsi'),
            'pendaftaran_noPendaftaran' => Yii::t('app', 'Pendaftaran No Pendaftaran'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendaftaranNoPendaftaran()
    {
        return $this->hasOne(Pendaftaran::className(), ['noPendaftaran' => 'pendaftaran_noPendaftaran']);
    }
}
