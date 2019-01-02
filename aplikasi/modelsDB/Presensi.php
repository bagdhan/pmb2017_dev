<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "presensi".
 *
 * @property int $id
 * @property string $pendaftaran_noPendaftaran
 * @property int $paketPendaftaran_id
 * @property string $even
 * @property string $dateCreate
 * @property string $dateUpdate
 *
 * @property PaketPendaftaran $paketPendaftaran
 * @property Pendaftaran $pendaftaranNoPendaftaran
 */
class Presensi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'presensi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pendaftaran_noPendaftaran', 'paketPendaftaran_id', 'dateCreate', 'dateUpdate'], 'required'],
            [['paketPendaftaran_id'], 'integer'],
            [['dateCreate', 'dateUpdate'], 'safe'],
            [['pendaftaran_noPendaftaran'], 'string', 'max' => 100],
            [['even'], 'string', 'max' => 250],
            [['paketPendaftaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaketPendaftaran::className(), 'targetAttribute' => ['paketPendaftaran_id' => 'id']],
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
            'paketPendaftaran_id' => Yii::t('app', 'Paket Pendaftaran ID'),
            'even' => Yii::t('app', 'Even'),
            'dateCreate' => Yii::t('app', 'Date Create'),
            'dateUpdate' => Yii::t('app', 'Date Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaketPendaftaran()
    {
        return $this->hasOne(PaketPendaftaran::className(), ['id' => 'paketPendaftaran_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendaftaranNoPendaftaran()
    {
        return $this->hasOne(Pendaftaran::className(), ['noPendaftaran' => 'pendaftaran_noPendaftaran']);
    }
}
