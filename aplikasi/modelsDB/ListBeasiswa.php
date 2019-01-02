<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "listBeasiswa".
 *
 * @property int $id
 * @property string $namaBeasiswa
 * @property string $pemberiBeasiswa
 * @property string $dateCreate
 * @property string $dateUpdate
 *
 * @property BeasiswaVerifikasi[] $beasiswaVerifikasis
 */
class ListBeasiswa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'listBeasiswa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['namaBeasiswa'], 'required'],
            [['dateCreate', 'dateUpdate'], 'safe'],
            [['namaBeasiswa'], 'string', 'max' => 100],
            [['pemberiBeasiswa'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'namaBeasiswa' => Yii::t('app', 'Nama Beasiswa'),
            'pemberiBeasiswa' => Yii::t('app', 'Pemberi Beasiswa'),
            'dateCreate' => Yii::t('app', 'Date Create'),
            'dateUpdate' => Yii::t('app', 'Date Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeasiswaVerifikasis()
    {
        return $this->hasMany(BeasiswaVerifikasi::className(), ['listBeasiswa_id' => 'id']);
    }
}
