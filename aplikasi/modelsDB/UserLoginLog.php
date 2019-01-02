<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "userLoginLog".
 *
 * @property int $id
 * @property string $dateLogin
 * @property string $dateLogout
 * @property string $ipLogin
 * @property int $userId
 *
 * @property User $user
 */
class UserLoginLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userLoginLog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dateLogin', 'userId'], 'required'],
            [['dateLogin', 'dateLogout'], 'safe'],
            [['userId'], 'integer'],
            [['ipLogin'], 'string', 'max' => 45],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'dateLogin' => Yii::t('app', 'Date Login'),
            'dateLogout' => Yii::t('app', 'Date Logout'),
            'ipLogin' => Yii::t('app', 'Ip Login'),
            'userId' => Yii::t('app', 'User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}
