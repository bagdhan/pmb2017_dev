<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "alamat".
 *
 * @property int $id
 * @property string $jalan
 * @property string $kodePos
 * @property string $rt
 * @property string $rw
 * @property string $desaKelurahan_kode
 *
 * @property DesaKelurahan $desaKelurahanKode
 * @property InstansiHasAlamat[] $instansiHasAlamats
 * @property Instansi[] $instansis
 * @property InstitusiHasAlamat[] $institusiHasAlamats
 * @property Institusi[] $institusis
 * @property OrangHasAlamat[] $orangHasAlamats
 * @property Orang[] $orangs
 */
class Alamat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alamat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jalan'], 'string', 'max' => 200],
            [['kodePos'], 'string', 'max' => 45],
            [['rt', 'rw', 'desaKelurahan_kode'], 'string', 'max' => 10],
            [['desaKelurahan_kode'], 'exist', 'skipOnError' => true, 'targetClass' => DesaKelurahan::className(), 'targetAttribute' => ['desaKelurahan_kode' => 'kode']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'jalan' => Yii::t('app', 'Jalan'),
            'kodePos' => Yii::t('app', 'Kode Pos'),
            'rt' => Yii::t('app', 'Rt'),
            'rw' => Yii::t('app', 'Rw'),
            'desaKelurahan_kode' => Yii::t('app', 'Desa Kelurahan Kode'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDesaKelurahanKode()
    {
        return $this->hasOne(DesaKelurahan::className(), ['kode' => 'desaKelurahan_kode']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstansiHasAlamats()
    {
        return $this->hasMany(InstansiHasAlamat::className(), ['alamat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstansis()
    {
        return $this->hasMany(Instansi::className(), ['id' => 'instansi_id'])->viaTable('instansi_has_alamat', ['alamat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstitusiHasAlamats()
    {
        return $this->hasMany(InstitusiHasAlamat::className(), ['alamat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstitusis()
    {
        return $this->hasMany(Institusi::className(), ['id' => 'institusi_id'])->viaTable('institusi_has_alamat', ['alamat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrangHasAlamats()
    {
        return $this->hasMany(OrangHasAlamat::className(), ['alamat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrangs()
    {
        return $this->hasMany(Orang::className(), ['id' => 'orang_id'])->viaTable('orang_has_alamat', ['alamat_id' => 'id']);
    }
}
