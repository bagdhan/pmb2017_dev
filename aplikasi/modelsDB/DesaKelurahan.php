<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "desaKelurahan".
 *
 * @property string $kode
 * @property string $namaID
 * @property string $namaEN
 * @property string $kecamatan_kode
 *
 * @property Alamat[] $alamats
 * @property Kecamatan $kecamatanKode
 */
class DesaKelurahan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'desaKelurahan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode', 'kecamatan_kode'], 'required'],
            [['kode', 'kecamatan_kode'], 'string', 'max' => 10],
            [['namaID', 'namaEN'], 'string', 'max' => 250],
            [['kode'], 'unique'],
            [['kecamatan_kode'], 'exist', 'skipOnError' => true, 'targetClass' => Kecamatan::className(), 'targetAttribute' => ['kecamatan_kode' => 'kode']],
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
            'kecamatan_kode' => Yii::t('app', 'Kecamatan Kode'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlamats()
    {
        return $this->hasMany(Alamat::className(), ['desaKelurahan_kode' => 'kode']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKecamatanKode()
    {
        return $this->hasOne(Kecamatan::className(), ['kode' => 'kecamatan_kode']);
    }
}
