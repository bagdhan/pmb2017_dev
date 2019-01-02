<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "finalStatus".
 *
 * @property int $id
 * @property string $tahun
 * @property string $pendaftaran_noPendaftaran
 * @property int $prosesSidang_id
 * @property int $statusMasuk_id
 *
 * @property Pendaftaran $pendaftaranNoPendaftaran
 * @property ProsesSidang $prosesSidang
 * @property StatusMasuk $statusMasuk
 */
class FinalStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'finalStatus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pendaftaran_noPendaftaran', 'statusMasuk_id'], 'required'],
            [['prosesSidang_id', 'statusMasuk_id'], 'integer'],
            [['tahun'], 'string', 'max' => 45],
            [['pendaftaran_noPendaftaran'], 'string', 'max' => 100],
            [['pendaftaran_noPendaftaran'], 'exist', 'skipOnError' => true, 'targetClass' => Pendaftaran::className(), 'targetAttribute' => ['pendaftaran_noPendaftaran' => 'noPendaftaran']],
            [['prosesSidang_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProsesSidang::className(), 'targetAttribute' => ['prosesSidang_id' => 'id']],
            [['statusMasuk_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusMasuk::className(), 'targetAttribute' => ['statusMasuk_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tahun' => Yii::t('app', 'Tahun'),
            'pendaftaran_noPendaftaran' => Yii::t('app', 'Pendaftaran No Pendaftaran'),
            'prosesSidang_id' => Yii::t('app', 'Proses Sidang ID'),
            'statusMasuk_id' => Yii::t('app', 'Status Masuk ID'),
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
    public function getProsesSidang()
    {
        return $this->hasOne(ProsesSidang::className(), ['id' => 'prosesSidang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusMasuk()
    {
        return $this->hasOne(StatusMasuk::className(), ['id' => 'statusMasuk_id']);
    }
}
