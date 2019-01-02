<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "surat".
 *
 * @property string $tanggal
 * @property string $nomor
 * @property string $perihal
 * @property string $tanggalPengumumanBuka
 * @property int $paketPendaftaran_id
 * @property int $statusPenerimaan 0 : Tolak 1 : Terima
 *
 * @property PaketPendaftaran $paketPendaftaran
 */
class Surat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'surat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tanggal', 'tanggalPengumumanBuka'], 'safe'],
            [['paketPendaftaran_id', 'statusPenerimaan'], 'required'],
            [['paketPendaftaran_id', 'statusPenerimaan'], 'integer'],
            [['nomor'], 'string', 'max' => 45],
            [['perihal'], 'string', 'max' => 250],
            [['paketPendaftaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaketPendaftaran::className(), 'targetAttribute' => ['paketPendaftaran_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tanggal' => Yii::t('app', 'Tanggal'),
            'nomor' => Yii::t('app', 'Nomor'),
            'perihal' => Yii::t('app', 'Perihal'),
            'tanggalPengumumanBuka' => Yii::t('app', 'Tanggal Pengumuman Buka'),
            'paketPendaftaran_id' => Yii::t('app', 'Paket Pendaftaran ID'),
            'statusPenerimaan' => Yii::t('app', 'Status Penerimaan'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaketPendaftaran()
    {
        return $this->hasOne(PaketPendaftaran::className(), ['id' => 'paketPendaftaran_id']);
    }
}
