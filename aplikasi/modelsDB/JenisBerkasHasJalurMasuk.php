<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "jenisBerkas_has_jalurMasuk".
 *
 * @property int $jenisBerkas_id
 * @property int $jalurMasuk_id
 *
 * @property JalurMasuk $jalurMasuk
 * @property JenisBerkas $jenisBerkas
 */
class JenisBerkasHasJalurMasuk extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenisBerkas_has_jalurMasuk';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jenisBerkas_id', 'jalurMasuk_id'], 'required'],
            [['jenisBerkas_id', 'jalurMasuk_id'], 'integer'],
            [['jalurMasuk_id'], 'exist', 'skipOnError' => true, 'targetClass' => JalurMasuk::className(), 'targetAttribute' => ['jalurMasuk_id' => 'id']],
            [['jenisBerkas_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisBerkas::className(), 'targetAttribute' => ['jenisBerkas_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'jenisBerkas_id' => Yii::t('app', 'Jenis Berkas ID'),
            'jalurMasuk_id' => Yii::t('app', 'Jalur Masuk ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJalurMasuk()
    {
        return $this->hasOne(JalurMasuk::className(), ['id' => 'jalurMasuk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisBerkas()
    {
        return $this->hasOne(JenisBerkas::className(), ['id' => 'jenisBerkas_id']);
    }
}
