<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "kecamatan".
 *
 * @property string $kode
 * @property string $namaID
 * @property string $namaEN
 * @property string $kabupatenKota_kode
 *
 * @property DesaKelurahan[] $desaKelurahans
 * @property KabupatenKota $kabupatenKotaKode
 */
class Kecamatan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kecamatan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode', 'kabupatenKota_kode'], 'required'],
            [['kode', 'kabupatenKota_kode'], 'string', 'max' => 10],
            [['namaID', 'namaEN'], 'string', 'max' => 250],
            [['kode'], 'unique'],
            [['kabupatenKota_kode'], 'exist', 'skipOnError' => true, 'targetClass' => KabupatenKota::className(), 'targetAttribute' => ['kabupatenKota_kode' => 'kode']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kode' => Yii::t('app', 'Kode'),
            'namaID' => Yii::t('app', 'Nama ID'),
            'namaEN' => Yii::t('app', 'Nama En'),
            'kabupatenKota_kode' => Yii::t('app', 'Kabupaten Kota Kode'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDesaKelurahans()
    {
        return $this->hasMany(DesaKelurahan::className(), ['kecamatan_kode' => 'kode']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKabupatenKotaKode()
    {
        return $this->hasOne(KabupatenKota::className(), ['kode' => 'kabupatenKota_kode']);
    }
}
