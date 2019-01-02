<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "pekerjaan_has_institusi".
 *
 * @property int $pekerjaan_id
 * @property int $institusi_id
 *
 * @property Institusi $institusi
 * @property Pekerjaan $pekerjaan
 */
class PekerjaanHasInstitusi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pekerjaan_has_institusi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pekerjaan_id', 'institusi_id'], 'required'],
            [['pekerjaan_id', 'institusi_id'], 'integer'],
            [['institusi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Institusi::className(), 'targetAttribute' => ['institusi_id' => 'id']],
            [['pekerjaan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pekerjaan::className(), 'targetAttribute' => ['pekerjaan_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pekerjaan_id' => Yii::t('app', 'Pekerjaan ID'),
            'institusi_id' => Yii::t('app', 'Institusi ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstitusi()
    {
        return $this->hasOne(Institusi::className(), ['id' => 'institusi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPekerjaan()
    {
        return $this->hasOne(Pekerjaan::className(), ['id' => 'pekerjaan_id']);
    }
}
