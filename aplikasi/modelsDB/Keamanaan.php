<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "keamanaan".
 *
 * @property int $id
 * @property string $namaGadisIbu
 * @property int $orang_id
 *
 * @property Orang $orang
 */
class Keamanaan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'keamanaan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['namaGadisIbu', 'orang_id'], 'required'],
            [['orang_id'], 'integer'],
            [['namaGadisIbu'], 'string', 'max' => 70],
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
            'namaGadisIbu' => Yii::t('app', 'Nama Gadis Ibu'),
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
