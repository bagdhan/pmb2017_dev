<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "karyaIlmiah".
 *
 * @property int $id
 * @property string $noPendaftaran
 * @property int $jurnalInternasional
 * @property int $jurnalNasionalAkreditasi
 * @property int $jurnalNasionalTakAkreditasi
 * @property int $belum
 *
 * @property Pendaftaran $noPendaftaran0
 */
class KaryaIlmiah extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'karyaIlmiah';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['noPendaftaran'], 'required'],
            [['jurnalInternasional', 'jurnalNasionalAkreditasi', 'jurnalNasionalTakAkreditasi', 'belum'], 'integer'],
            [['noPendaftaran'], 'string', 'max' => 100],
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
            'jurnalInternasional' => Yii::t('app', 'Jurnal Internasional'),
            'jurnalNasionalAkreditasi' => Yii::t('app', 'Jurnal Nasional Akreditasi'),
            'jurnalNasionalTakAkreditasi' => Yii::t('app', 'Jurnal Nasional Tak Akreditasi'),
            'belum' => Yii::t('app', 'Belum'),
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
