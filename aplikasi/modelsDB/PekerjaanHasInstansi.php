<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "pekerjaan_has_instansi".
 *
 * @property int $pekerjaan_id
 * @property int $instansi_id
 *
 * @property Instansi $instansi
 * @property Pekerjaan $pekerjaan
 */
class PekerjaanHasInstansi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pekerjaan_has_instansi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pekerjaan_id', 'instansi_id'], 'required'],
            [['pekerjaan_id', 'instansi_id'], 'integer'],
            [['instansi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Instansi::className(), 'targetAttribute' => ['instansi_id' => 'id']],
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
            'instansi_id' => Yii::t('app', 'Instansi ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstansi()
    {
        return $this->hasOne(Instansi::className(), ['id' => 'instansi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPekerjaan()
    {
        return $this->hasOne(Pekerjaan::className(), ['id' => 'pekerjaan_id']);
    }
}
