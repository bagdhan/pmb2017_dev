<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "pendaftaran_has_syaratTambahan".
 *
 * @property string $pendaftaran_noPendaftaran
 * @property integer $syaratTambahan_id
 *
 * @property Pendaftaran $pendaftaranNoPendaftaran
 * @property SyaratTambahan $syaratTambahan
 */
class PendaftaranHasSyaratTambahan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pendaftaran_has_syaratTambahan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pendaftaran_noPendaftaran', 'syaratTambahan_id'], 'required'],
            [['syaratTambahan_id'], 'integer'],
            [['pendaftaran_noPendaftaran'], 'string', 'max' => 100],
            [['pendaftaran_noPendaftaran'], 'exist', 'skipOnError' => true, 'targetClass' => Pendaftaran::className(), 'targetAttribute' => ['pendaftaran_noPendaftaran' => 'noPendaftaran']],
            [['syaratTambahan_id'], 'exist', 'skipOnError' => true, 'targetClass' => SyaratTambahan::className(), 'targetAttribute' => ['syaratTambahan_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pendaftaran_noPendaftaran' => Yii::t('app', 'Pendaftaran No Pendaftaran'),
            'syaratTambahan_id' => Yii::t('app', 'Syarat Tambahan ID'),
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
    public function getSyaratTambahan()
    {
        return $this->hasOne(SyaratTambahan::className(), ['id' => 'syaratTambahan_id']);
    }
}
