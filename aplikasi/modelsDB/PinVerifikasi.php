<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "pinVerifikasi".
 *
 * @property int $id
 * @property string $pin
 * @property string $dateVerifikasi
 * @property int $status
 * @property string $dateUpdate
 * @property string $ipVerifikasi
 * @property string $noPendaftaran
 *
 * @property Pendaftaran $noPendaftaran0
 */
class PinVerifikasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pinVerifikasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dateVerifikasi', 'dateUpdate'], 'safe'],
            [['status'], 'integer'],
            [['noPendaftaran'], 'required'],
            [['pin'], 'string', 'max' => 200],
            [['ipVerifikasi'], 'string', 'max' => 45],
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
            'pin' => Yii::t('app', 'Pin'),
            'dateVerifikasi' => Yii::t('app', 'Date Verifikasi'),
            'status' => Yii::t('app', 'Status'),
            'dateUpdate' => Yii::t('app', 'Date Update'),
            'ipVerifikasi' => Yii::t('app', 'Ip Verifikasi'),
            'noPendaftaran' => Yii::t('app', 'No Pendaftaran'),
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
