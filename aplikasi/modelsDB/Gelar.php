<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "gelar".
 *
 * @property int $id
 * @property string $nama
 * @property int $depanBelakang berisi 0 : gelar Depan, 1 : gelar Belakang
 * @property int $urutan
 * @property int $orang_id
 *
 * @property Orang $orang
 */
class Gelar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gelar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['depanBelakang', 'urutan', 'orang_id'], 'integer'],
            [['orang_id'], 'required'],
            [['nama'], 'string', 'max' => 50],
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
            'nama' => Yii::t('app', 'Nama'),
            'depanBelakang' => Yii::t('app', 'Depan Belakang'),
            'urutan' => Yii::t('app', 'Urutan'),
            'orang_id' => Yii::t('app', 'Orang ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrang()
    {
        return $this->hasOne(Orang::className(), ['id' => 'orang_id']);
    }
}
