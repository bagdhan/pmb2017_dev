<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "accessRole".
 *
 * @property int $id
 * @property string $roleName
 * @property string $roleDescription
 * @property string $ruleSettings
 * @property string $dateCreate
 * @property string $dateUpdate
 *
 * @property User[] $users
 */
class AccessRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accessRole';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['roleName', 'dateCreate', 'dateUpdate'], 'required'],
            [['ruleSettings'], 'string'],
            [['dateCreate', 'dateUpdate'], 'safe'],
            [['roleName'], 'string', 'max' => 60],
            [['roleDescription'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'roleName' => Yii::t('app', 'Role Name'),
            'roleDescription' => Yii::t('app', 'Role Description'),
            'ruleSettings' => Yii::t('app', 'Rule Settings'),
            'dateCreate' => Yii::t('app', 'Date Create'),
            'dateUpdate' => Yii::t('app', 'Date Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['accessRole_id' => 'id']);
    }
}
