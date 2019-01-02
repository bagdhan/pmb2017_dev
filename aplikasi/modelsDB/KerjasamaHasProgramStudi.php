<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "kerjasama_has_programStudi".
 *
 * @property int $id
 * @property int $kerjasama_id
 * @property int $programStudi_id
 *
 * @property Kerjasama $kerjasama
 * @property ProgramStudi $programStudi
 * @property PilihKerjasama[] $pilihKerjasamas
 * @property PendaftaranHasProgramStudi[] $pendaftaranHasProgramStudis
 */
class KerjasamaHasProgramStudi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kerjasama_has_programStudi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kerjasama_id', 'programStudi_id'], 'required'],
            [['kerjasama_id', 'programStudi_id'], 'integer'],
            [['kerjasama_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kerjasama::className(), 'targetAttribute' => ['kerjasama_id' => 'id']],
            [['programStudi_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgramStudi::className(), 'targetAttribute' => ['programStudi_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'kerjasama_id' => Yii::t('app', 'Kerjasama ID'),
            'programStudi_id' => Yii::t('app', 'Program Studi ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKerjasama()
    {
        return $this->hasOne(Kerjasama::className(), ['id' => 'kerjasama_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramStudi()
    {
        return $this->hasOne(ProgramStudi::className(), ['id' => 'programStudi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPilihKerjasamas()
    {
        return $this->hasMany(PilihKerjasama::className(), ['kerjasama_has_programStudi_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendaftaranHasProgramStudis()
    {
        return $this->hasMany(PendaftaranHasProgramStudi::className(), ['id' => 'pendaftaran_has_programStudi_id'])->viaTable('pilihKerjasama', ['kerjasama_has_programStudi_id' => 'id']);
    }
}
