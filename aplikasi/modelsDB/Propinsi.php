<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "propinsi".
 *
 * @property string $kode
 * @property string $namaID
 * @property string $namaEN
 *
 * @property KabupatenKota[] $kabupatenKotas
 */
class Propinsi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'propinsi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode'], 'required'],
            [['kode'], 'string', 'max' => 10],
            [['namaID', 'namaEN'], 'string', 'max' => 250],
            [['kode'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kode' => Yii::t('app', 'Kode'),
            'namaID' => Yii::t('app', 'Nama ID'),
            'namaEN' => Yii::t('app', 'Nama En'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKabupatenKotas()
    {
        return $this->hasMany(KabupatenKota::className(), ['propinsi_kode' => 'kode']);
    }
}
