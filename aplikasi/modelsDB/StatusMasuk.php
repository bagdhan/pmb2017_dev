<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "statusMasuk".
 *
 * @property int $id
 * @property string $nama
 *
 * @property FinalStatus[] $finalStatuses
 */
class StatusMasuk extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'statusMasuk';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinalStatuses()
    {
        return $this->hasMany(FinalStatus::className(), ['statusMasuk_id' => 'id']);
    }
}
