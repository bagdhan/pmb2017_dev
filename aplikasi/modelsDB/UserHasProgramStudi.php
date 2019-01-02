<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "user_has_programStudi".
 *
 * @property int $user_id
 * @property int $programStudi_id
 * @property int $lock_seleksi
 *
 * @property ProgramStudi $programStudi
 * @property User $user
 */
class UserHasProgramStudi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_has_programStudi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'programStudi_id'], 'required'],
            [['user_id', 'programStudi_id', 'lock_seleksi'], 'integer'],
            [['programStudi_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgramStudi::className(), 'targetAttribute' => ['programStudi_id' => 'id']],
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
            'programStudi_id' => Yii::t('app', 'Program Studi ID'),
            'lock_seleksi' => Yii::t('app', 'Lock Seleksi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramStudi()
    {
        return $this->hasOne(ProgramStudi::className(), ['id' => 'programStudi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
