<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "user_has_sidang".
 *
 * @property int $user_id
 * @property int $sidang_id
 *
 * @property Sidang $sidang
 * @property User $user
 */
class UserHasSidang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_has_sidang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'sidang_id'], 'required'],
            [['user_id', 'sidang_id'], 'integer'],
            [['sidang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sidang::className(), 'targetAttribute' => ['sidang_id' => 'id']],
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
            'sidang_id' => Yii::t('app', 'Sidang ID'),
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
