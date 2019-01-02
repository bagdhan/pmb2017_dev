<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "instansi".
 *
 * @property int $id
 * @property string $nama
 * @property string $tlp
 * @property string $fax
 * @property string $email
 * @property string $website
 * @property int $negara_id
 *
 * @property Negara $negara
 * @property InstansiHasAlamat[] $instansiHasAlamats
 * @property Alamat[] $alamats
 * @property PekerjaanHasInstansi[] $pekerjaanHasInstansis
 * @property Pekerjaan[] $pekerjaans
 */
class Instansi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'instansi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['negara_id'], 'integer'],
            [['nama'], 'string', 'max' => 250],
            [['tlp'], 'string', 'max' => 45],
            [['fax', 'website'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 100],
            [['negara_id'], 'exist', 'skipOnError' => true, 'targetClass' => Negara::className(), 'targetAttribute' => ['negara_id' => 'id']],
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
            'tlp' => Yii::t('app', 'Tlp'),
            'fax' => Yii::t('app', 'Fax'),
            'email' => Yii::t('app', 'Email'),
            'website' => Yii::t('app', 'Website'),
            'negara_id' => Yii::t('app', 'Negara ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNegara()
    {
        return $this->hasOne(Negara::className(), ['id' => 'negara_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstansiHasAlamats()
    {
        return $this->hasMany(InstansiHasAlamat::className(), ['instansi_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlamats()
    {
        return $this->hasMany(Alamat::className(), ['id' => 'alamat_id'])->viaTable('instansi_has_alamat', ['instansi_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPekerjaanHasInstansis()
    {
        return $this->hasMany(PekerjaanHasInstansi::className(), ['instansi_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPekerjaans()
    {
        return $this->hasMany(Pekerjaan::className(), ['id' => 'pekerjaan_id'])->viaTable('pekerjaan_has_instansi', ['instansi_id' => 'id']);
    }
}
