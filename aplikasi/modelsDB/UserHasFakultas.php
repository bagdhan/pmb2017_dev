<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "user_has_fakultas".
 *
 * @property int $user_id
 * @property int $fakultas_id
 * @property int $lock_seleksi
 *
 * @property Fakultas $fakultas
 * @property User $user
 */
class UserHasFakultas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_has_fakultas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'fakultas_id'], 'required'],
            [['user_id', 'fakultas_id', 'lock_seleksi'], 'integer'],
            [['fakultas_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fakultas::className(), 'targetAttribute' => ['fakultas_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'fakultas_id' => Yii::t('app', 'Fakultas ID'),
            'lock_seleksi' => Yii::t('app', 'Lock Seleksi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFakultas()
    {
        return $this->hasOne(Fakultas::className(), ['id' => 'fakultas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
