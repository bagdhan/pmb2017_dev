<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "jenisKontak".
 *
 * @property int $id
 * @property string $nama
 *
 * @property Kontak[] $kontaks
 */
class JenisKontak extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenisKontak';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKontaks()
    {
        return $this->hasMany(Kontak::className(), ['jenisKontak_id' => 'id']);
    }
}
