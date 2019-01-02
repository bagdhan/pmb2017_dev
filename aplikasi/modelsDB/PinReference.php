<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "pinReference".
 *
 * @property int $id
 * @property string $noPendaftaran
 * @property string $pin
 * @property int $use
 */
class PinReference extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pinReference';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['use'], 'integer'],
            [['noPendaftaran', 'pin'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'noPendaftaran' => Yii::t('app', 'No Pendaftaran'),
            'pin' => Yii::t('app', 'Pin'),
            'use' => Yii::t('app', 'Use'),
        ];
    }
}
