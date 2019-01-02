<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "pendaftaran_has_programStudi".
 *
 * @property int $id
 * @property string $pendaftaran_noPendaftaran
 * @property int $programStudi_id
 * @property int $urutan
 *
 * @property Pendaftaran $pendaftaranNoPendaftaran
 * @property ProgramStudi $programStudi
 * @property PilihKerjasama[] $pilihKerjasamas
 * @property KerjasamaHasProgramStudi[] $kerjasamaHasProgramStudis
 * @property ProsesSidang[] $prosesSidangs
 */
class PendaftaranHasProgramStudi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pendaftaran_has_programStudi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pendaftaran_noPendaftaran', 'programStudi_id'], 'required'],
            [['programStudi_id', 'urutan'], 'integer'],
            [['pendaftaran_noPendaftaran'], 'string', 'max' => 100],
            [['pendaftaran_noPendaftaran'], 'exist', 'skipOnError' => true, 'targetClass' => Pendaftaran::className(), 'targetAttribute' => ['pendaftaran_noPendaftaran' => 'noPendaftaran']],
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
            'pendaftaran_noPendaftaran' => Yii::t('app', 'Pendaftaran No Pendaftaran'),
            'programStudi_id' => Yii::t('app', 'Program Studi ID'),
            'urutan' => Yii::t('app', 'Urutan'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendaftaranNoPendaftaran()
    {
        return $this->hasOne(Pendaftaran::className(), ['noPendaftaran' => 'pendaftaran_noPendaftaran']);
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
        return $this->hasMany(PilihKerjasama::className(), ['pendaftaran_has_programStudi_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKerjasamaHasProgramStudis()
    {
        return $this->hasMany(KerjasamaHasProgramStudi::className(), ['id' => 'kerjasama_has_programStudi_id'])->viaTable('pilihKerjasama', ['pendaftaran_has_programStudi_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProsesSidangs()
    {
        return $this->hasMany(ProsesSidang::className(), ['pendaftaran_has_programStudi_id' => 'id']);
    }
}
