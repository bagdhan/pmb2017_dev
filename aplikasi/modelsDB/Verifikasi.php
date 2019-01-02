<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "verifikasi".
 *
 * @property int $id
 * @property int $noAntrian
 * @property string $pendaftaran_noPendaftaran
 * @property int $tahapVerifikasi_id
 * @property string $log
 * @property int $selesai
 * @property string $ket
 * @property string $dateCreate
 * @property int $paketVerifikasi_id
 *
 * @property PaketVerifikasi $paketVerifikasi
 * @property Pendaftaran $pendaftaranNoPendaftaran
 * @property TahapVerifikasi $tahapVerifikasi
 */
class Verifikasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'verifikasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['noAntrian', 'tahapVerifikasi_id', 'selesai', 'paketVerifikasi_id'], 'integer'],
            [['pendaftaran_noPendaftaran', 'tahapVerifikasi_id', 'dateCreate', 'paketVerifikasi_id'], 'required'],
            [['log', 'ket'], 'string'],
            [['dateCreate'], 'safe'],
            [['pendaftaran_noPendaftaran'], 'string', 'max' => 100],
            [['paketVerifikasi_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaketVerifikasi::className(), 'targetAttribute' => ['paketVerifikasi_id' => 'id']],
            [['pendaftaran_noPendaftaran'], 'exist', 'skipOnError' => true, 'targetClass' => Pendaftaran::className(), 'targetAttribute' => ['pendaftaran_noPendaftaran' => 'noPendaftaran']],
            [['tahapVerifikasi_id'], 'exist', 'skipOnError' => true, 'targetClass' => TahapVerifikasi::className(), 'targetAttribute' => ['tahapVerifikasi_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'noAntrian' => Yii::t('app', 'No Antrian'),
            'pendaftaran_noPendaftaran' => Yii::t('app', 'Pendaftaran No Pendaftaran'),
            'tahapVerifikasi_id' => Yii::t('app', 'Tahap Verifikasi ID'),
            'log' => Yii::t('app', 'Log'),
            'selesai' => Yii::t('app', 'Selesai'),
            'ket' => Yii::t('app', 'Ket'),
            'dateCreate' => Yii::t('app', 'Date Create'),
            'paketVerifikasi_id' => Yii::t('app', 'Paket Verifikasi ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaketVerifikasi()
    {
        return $this->hasOne(PaketVerifikasi::className(), ['id' => 'paketVerifikasi_id']);
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
    public function getTahapVerifikasi()
    {
        return $this->hasOne(TahapVerifikasi::className(), ['id' => 'tahapVerifikasi_id']);
    }
}
