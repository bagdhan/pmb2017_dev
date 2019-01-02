<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $passwordHash
 * @property string $authKey
 * @property string $accessToken
 * @property int $status
 * @property string $passwordResetToken
 * @property string $email
 * @property string $dateCreate
 * @property string $dateUpdate
 * @property int $accessRole_id
 * @property int $orang_id
 *
 * @property Profile $profile
 * @property AccessRole $accessRole
 * @property Orang $orang
 * @property UserLoginLog[] $userLoginLogs
 * @property UserHasDepartemen[] $userHasDepartemens
 * @property Departemen[] $departemens
 * @property UserHasFakultas[] $userHasFakultas
 * @property Fakultas[] $fakultas
 * @property UserHasProgramStudi[] $userHasProgramStudis
 * @property ProgramStudi[] $programStudis
 * @property UserHasSidang[] $userHasSidangs
 * @property Sidang[] $sidangs
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'authKey', 'dateCreate', 'dateUpdate', 'accessRole_id'], 'required'],
            [['status', 'accessRole_id', 'orang_id'], 'integer'],
            [['dateCreate', 'dateUpdate'], 'safe'],
            [['username', 'email'], 'string', 'max' => 100],
            [['passwordHash', 'authKey', 'accessToken', 'passwordResetToken'], 'string', 'max' => 200],
            [['username'], 'unique'],
            [['authKey'], 'unique'],
            [['accessRole_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccessRole::className(), 'targetAttribute' => ['accessRole_id' => 'id']],
            [['orang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orang::className(), 'targetAttribute' => ['orang_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'passwordHash' => Yii::t('app', 'Password Hash'),
            'authKey' => Yii::t('app', 'Auth Key'),
            'accessToken' => Yii::t('app', 'Access Token'),
            'status' => Yii::t('app', 'Status'),
            'passwordResetToken' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'dateCreate' => Yii::t('app', 'Date Create'),
            'dateUpdate' => Yii::t('app', 'Date Update'),
            'accessRole_id' => Yii::t('app', 'Access Role ID'),
            'orang_id' => Yii::t('app', 'Orang ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccessRole()
    {
        return $this->hasOne(AccessRole::className(), ['id' => 'accessRole_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrang()
    {
        return $this->hasOne(Orang::className(), ['id' => 'orang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserLoginLogs()
    {
        return $this->hasMany(UserLoginLog::className(), ['userId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasDepartemens()
    {
        return $this->hasMany(UserHasDepartemen::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartemens()
    {
        return $this->hasMany(Departemen::className(), ['id' => 'departemen_id'])->viaTable('user_has_departemen', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasFakultas()
    {
        return $this->hasMany(UserHasFakultas::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFakultas()
    {
        return $this->hasMany(Fakultas::className(), ['id' => 'fakultas_id'])->viaTable('user_has_fakultas', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasProgramStudis()
    {
        return $this->hasMany(UserHasProgramStudi::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramStudis()
    {
        return $this->hasMany(ProgramStudi::className(), ['id' => 'programStudi_id'])->viaTable('user_has_programStudi', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasSidangs()
    {
        return $this->hasMany(UserHasSidang::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSidangs()
    {
        return $this->hasMany(Sidang::className(), ['id' => 'sidang_id'])->viaTable('user_has_sidang', ['user_id' => 'id']);
    }
}
