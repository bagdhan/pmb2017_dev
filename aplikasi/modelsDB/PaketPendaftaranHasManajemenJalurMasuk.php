<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "paketPendaftaran_has_manajemenJalurMasuk".
 *
 * @property int $paketPendaftaran_id
 * @property int $manajemenJalurMasuk_id
 *
 * @property ManajemenJalurMasuk $manajemenJalurMasuk
 * @property PaketPendaftaran $paketPendaftaran
 */
class PaketPendaftaranHasManajemenJalurMasuk extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paketPendaftaran_has_manajemenJalurMasuk';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['paketPendaftaran_id', 'manajemenJalurMasuk_id'], 'required'],
            [['paketPendaftaran_id', 'manajemenJalurMasuk_id'], 'integer'],
            [['manajemenJalurMasuk_id'], 'exist', 'skipOnError' => true, 'targetClass' => ManajemenJalurMasuk::className(), 'targetAttribute' => ['manajemenJalurMasuk_id' => 'id']],
            [['paketPendaftaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaketPendaftaran::className(), 'targetAttribute' => ['paketPendaftaran_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'paketPendaftaran_id' => Yii::t('app', 'Paket Pendaftaran ID'),
            'manajemenJalurMasuk_id' => Yii::t('app', 'Manajemen Jalur Masuk ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManajemenJalurMasuk()
    {
        return $this->hasOne(ManajemenJalurMasuk::className(), ['id' => 'manajemenJalurMasuk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaketPendaftaran()
    {
        return $this->hasOne(PaketPendaftaran::className(), ['id' => 'paketPendaftaran_id']);
    }
}
