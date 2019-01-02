<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "biayaPendidikan".
 *
 * @property int $id
 * @property double $jumlah
 * @property int $jenisBiaya_id
 *
 * @property JenisBiaya $jenisBiaya
 * @property ManajemenJalurMasuk[] $manajemenJalurMasuks
 */
class BiayaPendidikan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'biayaPendidikan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jumlah'], 'number'],
            [['jenisBiaya_id'], 'required'],
            [['jenisBiaya_id'], 'integer'],
            [['jenisBiaya_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisBiaya::className(), 'targetAttribute' => ['jenisBiaya_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'jumlah' => Yii::t('app', 'Jumlah'),
            'jenisBiaya_id' => Yii::t('app', 'Jenis Biaya ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisBiaya()
    {
        return $this->hasOne(JenisBiaya::className(), ['id' => 'jenisBiaya_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManajemenJalurMasuks()
    {
        return $this->hasMany(ManajemenJalurMasuk::className(), ['biayaPendidikan_id' => 'id']);
    }
}
