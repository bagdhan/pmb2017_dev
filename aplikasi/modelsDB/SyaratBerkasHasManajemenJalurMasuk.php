<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "syaratBerkas_has_manajemenJalurMasuk".
 *
 * @property int $syaratBerkas_id
 * @property int $manajemenJalurMasuk_id
 *
 * @property ManajemenJalurMasuk $manajemenJalurMasuk
 * @property SyaratBerkas $syaratBerkas
 */
class SyaratBerkasHasManajemenJalurMasuk extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'syaratBerkas_has_manajemenJalurMasuk';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['syaratBerkas_id', 'manajemenJalurMasuk_id'], 'required'],
            [['syaratBerkas_id', 'manajemenJalurMasuk_id'], 'integer'],
            [['manajemenJalurMasuk_id'], 'exist', 'skipOnError' => true, 'targetClass' => ManajemenJalurMasuk::className(), 'targetAttribute' => ['manajemenJalurMasuk_id' => 'id']],
            [['syaratBerkas_id'], 'exist', 'skipOnError' => true, 'targetClass' => SyaratBerkas::className(), 'targetAttribute' => ['syaratBerkas_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'syaratBerkas_id' => Yii::t('app', 'Syarat Berkas ID'),
            'manajemenJalurMasuk_id' => Yii::t('app', 'Manajemen Jalur Masuk ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManajemenJalurMasuk()
    {
        return $this->hasOne(ManajemenJalurMasuk::className(), ['id' => 'manajemenJalurMasuk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSyaratBerkas()
    {
        return $this->hasOne(SyaratBerkas::className(), ['id' => 'syaratBerkas_id']);
    }
}
