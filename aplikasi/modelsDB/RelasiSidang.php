<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "relasiSidang".
 *
 * @property int $sidang_id
 * @property int $child
 * @property int $seleksi
 *
 * @property Sidang $sidang
 * @property Sidang $child0
 */
class RelasiSidang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'relasiSidang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sidang_id', 'child'], 'required'],
            [['sidang_id', 'child', 'seleksi'], 'integer'],
            [['sidang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sidang::className(), 'targetAttribute' => ['sidang_id' => 'id']],
            [['child'], 'exist', 'skipOnError' => true, 'targetClass' => Sidang::className(), 'targetAttribute' => ['child' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sidang_id' => Yii::t('app', 'Sidang ID'),
            'child' => Yii::t('app', 'Child'),
            'seleksi' => Yii::t('app', 'Seleksi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSidang()
    {
        return $this->hasOne(Sidang::className(), ['id' => 'sidang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChild0()
    {
        return $this->hasOne(Sidang::className(), ['id' => 'child']);
    }
}
