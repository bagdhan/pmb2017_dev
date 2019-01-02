<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "kontak".
 *
 * @property int $id
 * @property string $kontak
 * @property int $jenisKontak_id
 * @property int $orang_id
 * @property string $waktuBuat
 * @property string $waktuUbah
 *
 * @property JenisKontak $jenisKontak
 * @property Orang $orang
 */
class Kontak extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kontak';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jenisKontak_id', 'orang_id'], 'required'],
            [['jenisKontak_id', 'orang_id'], 'integer'],
            [['waktuBuat', 'waktuUbah'], 'safe'],
            [['kontak'], 'string', 'max' => 100],
            [['jenisKontak_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisKontak::className(), 'targetAttribute' => ['jenisKontak_id' => 'id']],
            [['orang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orang::className(), 'targetAttribute' => ['orang_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'kontak' => Yii::t('app', 'Kontak'),
            'jenisKontak_id' => Yii::t('app', 'Jenis Kontak ID'),
            'orang_id' => Yii::t('app', 'Orang ID'),
            'waktuBuat' => Yii::t('app', 'Waktu Buat'),
            'waktuUbah' => Yii::t('app', 'Waktu Ubah'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisKontak()
    {
        return $this->hasOne(JenisKontak::className(), ['id' => 'jenisKontak_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrang()
    {
        return $this->hasOne(Orang::className(), ['id' => 'orang_id']);
    }
}
