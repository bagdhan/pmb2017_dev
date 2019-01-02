<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "kabupatenKota".
 *
 * @property string $kode
 * @property string $namaID
 * @property string $namaEN
 * @property string $propinsi_kode
 *
 * @property Propinsi $propinsiKode
 * @property Kecamatan[] $kecamatans
 */
class KabupatenKota extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kabupatenKota';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode', 'propinsi_kode'], 'required'],
            [['kode', 'propinsi_kode'], 'string', 'max' => 10],
            [['namaID', 'namaEN'], 'string', 'max' => 250],
            [['kode'], 'unique'],
            [['propinsi_kode'], 'exist', 'skipOnError' => true, 'targetClass' => Propinsi::className(), 'targetAttribute' => ['propinsi_kode' => 'kode']],
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
            'propinsi_kode' => Yii::t('app', 'Propinsi Kode'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropinsiKode()
    {
        return $this->hasOne(Propinsi::className(), ['kode' => 'propinsi_kode']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKecamatans()
    {
        return $this->hasMany(Kecamatan::className(), ['kabupatenKota_kode' => 'kode']);
    }
}
