<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "identitas".
 *
 * @property int $id
 * @property string $identitas
 * @property int $jenisIdentitas_id
 * @property int $orang_id
 * @property string $waktuBuat
 * @property string $waktuUbah
 *
 * @property JenisIdentitas $jenisIdentitas
 * @property Orang $orang
 */
class Identitas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'identitas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jenisIdentitas_id', 'orang_id'], 'required'],
            [['jenisIdentitas_id', 'orang_id'], 'integer'],
            [['waktuBuat', 'waktuUbah'], 'safe'],
            [['identitas'], 'string', 'max' => 150],
            [['jenisIdentitas_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisIdentitas::className(), 'targetAttribute' => ['jenisIdentitas_id' => 'id']],
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
            'identitas' => Yii::t('app', 'Identitas'),
            'jenisIdentitas_id' => Yii::t('app', 'Jenis Identitas ID'),
            'orang_id' => Yii::t('app', 'Orang ID'),
            'waktuBuat' => Yii::t('app', 'Waktu Buat'),
            'waktuUbah' => Yii::t('app', 'Waktu Ubah'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisIdentitas()
    {
        return $this->hasOne(JenisIdentitas::className(), ['id' => 'jenisIdentitas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrang()
    {
        return $this->hasOne(Orang::className(), ['id' => 'orang_id']);
    }
}
