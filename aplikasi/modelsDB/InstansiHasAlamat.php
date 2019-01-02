<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "instansi_has_alamat".
 *
 * @property int $instansi_id
 * @property int $alamat_id
 *
 * @property Alamat $alamat
 * @property Instansi $instansi
 */
class InstansiHasAlamat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'instansi_has_alamat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['instansi_id', 'alamat_id'], 'required'],
            [['instansi_id', 'alamat_id'], 'integer'],
            [['alamat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Alamat::className(), 'targetAttribute' => ['alamat_id' => 'id']],
            [['instansi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Instansi::className(), 'targetAttribute' => ['instansi_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'instansi_id' => Yii::t('app', 'Instansi ID'),
            'alamat_id' => Yii::t('app', 'Alamat ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlamat()
    {
        return $this->hasOne(Alamat::className(), ['id' => 'alamat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstansi()
    {
        return $this->hasOne(Instansi::className(), ['id' => 'instansi_id']);
    }
}
