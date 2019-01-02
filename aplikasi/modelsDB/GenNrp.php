<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "genNrp".
 *
 * @property int $id
 * @property string $noPendaftaran
 * @property string $kodeProdi
 * @property string $tahunMasuk
 * @property int $kodeKhusus
 * @property int $noUrut
 * @property int $kodeMasuk
 * @property int $lockNrp
 * @property string $dateCreate
 * @property string $dateUpdate
 *
 * @property Pendaftaran $noPendaftaran0
 */
class GenNrp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'genNrp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['noPendaftaran', 'kodeProdi', 'tahunMasuk', 'kodeKhusus', 'noUrut', 'kodeMasuk', 'lockNrp', 'dateCreate', 'dateUpdate'], 'required'],
            [['kodeKhusus', 'noUrut', 'kodeMasuk', 'lockNrp'], 'integer'],
            [['dateCreate', 'dateUpdate'], 'safe'],
            [['noPendaftaran'], 'string', 'max' => 100],
            [['kodeProdi'], 'string', 'max' => 7],
            [['tahunMasuk'], 'string', 'max' => 4],
            [['noPendaftaran'], 'exist', 'skipOnError' => true, 'targetClass' => Pendaftaran::className(), 'targetAttribute' => ['noPendaftaran' => 'noPendaftaran']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'noPendaftaran' => Yii::t('app', 'No Pendaftaran'),
            'kodeProdi' => Yii::t('app', 'Kode Prodi'),
            'tahunMasuk' => Yii::t('app', 'Tahun Masuk'),
            'kodeKhusus' => Yii::t('app', 'Kode Khusus'),
            'noUrut' => Yii::t('app', 'No Urut'),
            'kodeMasuk' => Yii::t('app', 'Kode Masuk'),
            'lockNrp' => Yii::t('app', 'Lock Nrp'),
            'dateCreate' => Yii::t('app', 'Date Create'),
            'dateUpdate' => Yii::t('app', 'Date Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoPendaftaran0()
    {
        return $this->hasOne(Pendaftaran::className(), ['noPendaftaran' => 'noPendaftaran']);
    }
}
