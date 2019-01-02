<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "agama".
 *
 * @property int $id
 * @property string $status
 *
 * @property Orang[] $orangs
 */
class Agama extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agama';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agama'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'agama' => Yii::t('app', 'Agama'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrangs()
    {
        return $this->hasMany(Orang::className(), ['agama_id' => 'id']);
    }
}
