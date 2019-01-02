<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "program".
 *
 * @property int $id
 * @property string $nama
 * @property string $deskripsi
 *
 * @property ManajemenJalurMasuk[] $manajemenJalurMasuks
 */
class Program extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'program';
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
    public function getManajemenJalurMasuks()
    {
        return $this->hasMany(ManajemenJalurMasuk::className(), ['program_id' => 'id']);
    }
}
