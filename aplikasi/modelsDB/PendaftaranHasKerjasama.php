<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "pendaftaran_has_kerjasama".
 *
 * @property string $pendaftaran_noPendaftaran
 * @property int $kerjasama_id
 *
 * @property Kerjasama $kerjasama
 * @property Pendaftaran $pendaftaranNoPendaftaran
 */
class PendaftaranHasKerjasama extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pendaftaran_has_kerjasama';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pendaftaran_noPendaftaran', 'kerjasama_id'], 'required'],
            [['kerjasama_id'], 'integer'],
            [['pendaftaran_noPendaftaran'], 'string', 'max' => 100],
            [['kerjasama_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kerjasama::className(), 'targetAttribute' => ['kerjasama_id' => 'id']],
            [['pendaftaran_noPendaftaran'], 'exist', 'skipOnError' => true, 'targetClass' => Pendaftaran::className(), 'targetAttribute' => ['pendaftaran_noPendaftaran' => 'noPendaftaran']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pendaftaran_noPendaftaran' => Yii::t('app', 'Pendaftaran No Pendaftaran'),
            'kerjasama_id' => Yii::t('app', 'Kerjasama ID'),
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
    public function getPendaftaranNoPendaftaran()
    {
        return $this->hasOne(Pendaftaran::className(), ['noPendaftaran' => 'pendaftaran_noPendaftaran']);
    }
}
