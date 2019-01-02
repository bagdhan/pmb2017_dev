<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "beasiswaVerifikasi".
 *
 * @property int $id
 * @property string $pendaftaran_noPendaftaran
 * @property int $listBeasiswa_id
 * @property string $ket
 *
 * @property ListBeasiswa $listBeasiswa
 * @property Pendaftaran $pendaftaranNoPendaftaran
 */
class BeasiswaVerifikasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'beasiswaVerifikasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pendaftaran_noPendaftaran', 'listBeasiswa_id'], 'required'],
            [['listBeasiswa_id'], 'integer'],
            [['ket'], 'string'],
            [['pendaftaran_noPendaftaran'], 'string', 'max' => 100],
            [['listBeasiswa_id'], 'exist', 'skipOnError' => true, 'targetClass' => ListBeasiswa::className(), 'targetAttribute' => ['listBeasiswa_id' => 'id']],
            [['pendaftaran_noPendaftaran'], 'exist', 'skipOnError' => true, 'targetClass' => Pendaftaran::className(), 'targetAttribute' => ['pendaftaran_noPendaftaran' => 'noPendaftaran']],
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
            'listBeasiswa_id' => Yii::t('app', 'List Beasiswa ID'),
            'ket' => Yii::t('app', 'Ket'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListBeasiswa()
    {
        return $this->hasOne(ListBeasiswa::className(), ['id' => 'listBeasiswa_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendaftaranNoPendaftaran()
    {
        return $this->hasOne(Pendaftaran::className(), ['noPendaftaran' => 'pendaftaran_noPendaftaran']);
    }
}
