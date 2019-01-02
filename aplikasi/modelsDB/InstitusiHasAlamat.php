<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "institusi_has_alamat".
 *
 * @property int $institusi_id
 * @property int $alamat_id
 *
 * @property Alamat $alamat
 * @property Institusi $institusi
 */
class InstitusiHasAlamat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'institusi_has_alamat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['institusi_id', 'alamat_id'], 'required'],
            [['institusi_id', 'alamat_id'], 'integer'],
            [['alamat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Alamat::className(), 'targetAttribute' => ['alamat_id' => 'id']],
            [['institusi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Institusi::className(), 'targetAttribute' => ['institusi_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'institusi_id' => Yii::t('app', 'Institusi ID'),
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
    public function getInstitusi()
    {
        return $this->hasOne(Institusi::className(), ['id' => 'institusi_id']);
    }
}
