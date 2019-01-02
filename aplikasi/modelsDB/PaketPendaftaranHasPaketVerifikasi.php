<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "paketPendaftaran_has_paketVerifikasi".
 *
 * @property int $paketPendaftaran_id
 * @property int $paketVerifikasi_id
 *
 * @property PaketPendaftaran $paketPendaftaran
 * @property PaketVerifikasi $paketVerifikasi
 */
class PaketPendaftaranHasPaketVerifikasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paketPendaftaran_has_paketVerifikasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['paketPendaftaran_id', 'paketVerifikasi_id'], 'required'],
            [['paketPendaftaran_id', 'paketVerifikasi_id'], 'integer'],
            [['paketPendaftaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaketPendaftaran::className(), 'targetAttribute' => ['paketPendaftaran_id' => 'id']],
            [['paketVerifikasi_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaketVerifikasi::className(), 'targetAttribute' => ['paketVerifikasi_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'paketPendaftaran_id' => Yii::t('app', 'Paket Pendaftaran ID'),
            'paketVerifikasi_id' => Yii::t('app', 'Paket Verifikasi ID'),
        ];
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
    public function getPaketVerifikasi()
    {
        return $this->hasOne(PaketVerifikasi::className(), ['id' => 'paketVerifikasi_id']);
    }
}
