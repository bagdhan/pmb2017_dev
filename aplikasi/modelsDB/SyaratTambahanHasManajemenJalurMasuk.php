<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "syaratTambahan_has_manajemenJalurMasuk".
 *
 * @property int $manajemenJalurMasuk_id
 * @property int $jenisSyaratTambahan_id
 * @property int $priority
 *
 * @property JenisSyaratTambahan $jenisSyaratTambahan
 * @property ManajemenJalurMasuk $manajemenJalurMasuk
 */
class SyaratTambahanHasManajemenJalurMasuk extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'syaratTambahan_has_manajemenJalurMasuk';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manajemenJalurMasuk_id', 'jenisSyaratTambahan_id'], 'required'],
            [['manajemenJalurMasuk_id', 'jenisSyaratTambahan_id', 'priority'], 'integer'],
            [['jenisSyaratTambahan_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisSyaratTambahan::className(), 'targetAttribute' => ['jenisSyaratTambahan_id' => 'id']],
            [['manajemenJalurMasuk_id'], 'exist', 'skipOnError' => true, 'targetClass' => ManajemenJalurMasuk::className(), 'targetAttribute' => ['manajemenJalurMasuk_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'manajemenJalurMasuk_id' => Yii::t('app', 'Manajemen Jalur Masuk ID'),
            'jenisSyaratTambahan_id' => Yii::t('app', 'Jenis Syarat Tambahan ID'),
            'priority' => Yii::t('app', 'Priority'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisSyaratTambahan()
    {
        return $this->hasOne(JenisSyaratTambahan::className(), ['id' => 'jenisSyaratTambahan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManajemenJalurMasuk()
    {
        return $this->hasOne(ManajemenJalurMasuk::className(), ['id' => 'manajemenJalurMasuk_id']);
    }
}
