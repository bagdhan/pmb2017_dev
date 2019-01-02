<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "pilihKerjasama".
 *
 * @property int $pendaftaran_has_programStudi_id
 * @property int $kerjasama_has_programStudi_id
 *
 * @property PendaftaranHasProgramStudi $pendaftaranHasProgramStudi
 * @property KerjasamaHasProgramStudi $kerjasamaHasProgramStudi
 */
class PilihKerjasama extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pilihKerjasama';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pendaftaran_has_programStudi_id', 'kerjasama_has_programStudi_id'], 'required'],
            [['pendaftaran_has_programStudi_id', 'kerjasama_has_programStudi_id'], 'integer'],
            [['pendaftaran_has_programStudi_id'], 'exist', 'skipOnError' => true, 'targetClass' => PendaftaranHasProgramStudi::className(), 'targetAttribute' => ['pendaftaran_has_programStudi_id' => 'id']],
            [['kerjasama_has_programStudi_id'], 'exist', 'skipOnError' => true, 'targetClass' => KerjasamaHasProgramStudi::className(), 'targetAttribute' => ['kerjasama_has_programStudi_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pendaftaran_has_programStudi_id' => Yii::t('app', 'Pendaftaran Has Program Studi ID'),
            'kerjasama_has_programStudi_id' => Yii::t('app', 'Kerjasama Has Program Studi ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendaftaranHasProgramStudi()
    {
        return $this->hasOne(PendaftaranHasProgramStudi::className(), ['id' => 'pendaftaran_has_programStudi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKerjasamaHasProgramStudi()
    {
        return $this->hasOne(KerjasamaHasProgramStudi::className(), ['id' => 'kerjasama_has_programStudi_id']);
    }
}
