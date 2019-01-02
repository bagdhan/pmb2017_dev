<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "user_has_departemen".
 *
 * @property int $user_id
 * @property int $departemen_id
 * @property int $lock_seleksi
 *
 * @property Departemen $departemen
 * @property User $user
 */
class UserHasDepartemen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_has_departemen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'departemen_id'], 'required'],
            [['user_id', 'departemen_id', 'lock_seleksi'], 'integer'],
            [['departemen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Departemen::className(), 'targetAttribute' => ['departemen_id' => 'id']],
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
            'departemen_id' => Yii::t('app', 'Departemen ID'),
            'lock_seleksi' => Yii::t('app', 'Lock Seleksi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartemen()
    {
        return $this->hasOne(Departemen::className(), ['id' => 'departemen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
