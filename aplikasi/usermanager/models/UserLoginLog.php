<?php

namespace app\usermanager\models;

use app\models\CostumDate;
use Yii;

/**
 * This is the model class for table "userLoginLog".
 *
 * @property integer $id
 * @property string $dateLogin
 * @property string $dateLogout
 * @property string $ipLogin
 * @property integer $userId
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

    public static function setLogin($userID)
    {
        CostumDate::tiemZone();
        $log = new self();
        $log->dateLogin = date('Y-m-d H:i:s');
        $log->userId = $userID;
        $log->ipLogin = Yii::$app->request->userIP;
        return $log->save();
    }

    public static function setLogout($userID)
    {
        CostumDate::tiemZone();
        $log = self::find()->where([
            'userId' => $userID,
            'ipLogin' => Yii::$app->request->userIP])
            ->orderBy('dateLogin DESC')->one();
        if (empty($log)) {

        } else {
            $log->dateLogout = date('Y-m-d H:i:s');
            return $log->save();
        }
        return false;
    }
}
