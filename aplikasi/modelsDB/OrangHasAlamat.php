<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "orang_has_alamat".
 *
 * @property int $orang_id
 * @property int $alamat_id
 *
 * @property Alamat $alamat
 * @property Orang $orang
 */
class OrangHasAlamat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orang_has_alamat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orang_id', 'alamat_id'], 'required'],
            [['orang_id', 'alamat_id'], 'integer'],
            [['alamat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Alamat::className(), 'targetAttribute' => ['alamat_id' => 'id']],
            [['orang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orang::className(), 'targetAttribute' => ['orang_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'orang_id' => Yii::t('app', 'Orang ID'),
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
    public function getOrang()
    {
        return $this->hasOne(Orang::className(), ['id' => 'orang_id']);
    }
}
