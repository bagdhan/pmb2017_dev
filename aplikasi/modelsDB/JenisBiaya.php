<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "jenisBiaya".
 *
 * @property int $id
 * @property string $nama
 * @property string $deskripsi
 *
 * @property BiayaPendidikan[] $biayaPendidikans
 */
class JenisBiaya extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenisBiaya';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deskripsi'], 'string'],
            [['nama'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nama' => Yii::t('app', 'Nama'),
            'deskripsi' => Yii::t('app', 'Deskripsi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBiayaPendidikans()
    {
        return $this->hasMany(BiayaPendidikan::className(), ['jenisBiaya_id' => 'id']);
    }
}
