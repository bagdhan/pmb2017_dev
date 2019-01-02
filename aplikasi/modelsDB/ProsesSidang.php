<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "prosesSidang".
 *
 * @property int $id
 * @property int $sidang_id
 * @property int $hasilKeputusan_id
 * @property string $keterangan
 * @property string $dateCreate
 * @property string $historyUpdate
 * @property int $pendaftaran_has_programStudi_id
 *
 * @property FinalStatus[] $finalStatuses
 * @property Sidang $sidang
 * @property HasilKeputusan $hasilKeputusan
 * @property PendaftaranHasProgramStudi $pendaftaranHasProgramStudi
 */
class ProsesSidang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prosesSidang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sidang_id', 'pendaftaran_has_programStudi_id'], 'required'],
            [['sidang_id', 'hasilKeputusan_id', 'pendaftaran_has_programStudi_id'], 'integer'],
            [['keterangan', 'historyUpdate'], 'string'],
            [['dateCreate'], 'safe'],
            [['sidang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sidang::className(), 'targetAttribute' => ['sidang_id' => 'id']],
            [['hasilKeputusan_id'], 'exist', 'skipOnError' => true, 'targetClass' => HasilKeputusan::className(), 'targetAttribute' => ['hasilKeputusan_id' => 'id']],
            [['pendaftaran_has_programStudi_id'], 'exist', 'skipOnError' => true, 'targetClass' => PendaftaranHasProgramStudi::className(), 'targetAttribute' => ['pendaftaran_has_programStudi_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sidang_id' => Yii::t('app', 'Sidang ID'),
            'hasilKeputusan_id' => Yii::t('app', 'Hasil Keputusan ID'),
            'keterangan' => Yii::t('app', 'Keterangan'),
            'dateCreate' => Yii::t('app', 'Date Create'),
            'historyUpdate' => Yii::t('app', 'History Update'),
            'pendaftaran_has_programStudi_id' => Yii::t('app', 'Pendaftaran Has Program Studi ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinalStatuses()
    {
        return $this->hasMany(FinalStatus::className(), ['prosesSidang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSidang()
    {
        return $this->hasOne(Sidang::className(), ['id' => 'sidang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHasilKeputusan()
    {
        return $this->hasOne(HasilKeputusan::className(), ['id' => 'hasilKeputusan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendaftaranHasProgramStudi()
    {
        return $this->hasOne(PendaftaranHasProgramStudi::className(), ['id' => 'pendaftaran_has_programStudi_id']);
    }
}
