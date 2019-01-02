<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "negara".
 *
 * @property int $id
 * @property string $nama
 * @property string $inisial
 * @property string $kode
 *
 * @property Instansi[] $instansis
 * @property Institusi[] $institusis
 * @property Orang[] $orangs
 */
class Negara extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'negara';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['nama'], 'string', 'max' => 100],
            [['inisial'], 'string', 'max' => 45],
            [['kode'], 'string', 'max' => 10],
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
            'inisial' => Yii::t('app', 'Inisial'),
            'kode' => Yii::t('app', 'Kode'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstansis()
    {
        return $this->hasMany(Instansi::className(), ['negara_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstitusis()
    {
        return $this->hasMany(Institusi::className(), ['negara_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrangs()
    {
        return $this->hasMany(Orang::className(), ['negara_id' => 'id']);
    }
}
