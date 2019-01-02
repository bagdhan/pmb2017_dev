<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "statusPerkawinan".
 *
 * @property int $id
 * @property string $status
 *
 * @property Orang[] $orangs
 */
class StatusPerkawinan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'statusPerkawinan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrangs()
    {
        return $this->hasMany(Orang::className(), ['statusPerkawinan_id' => 'id']);
    }
}
