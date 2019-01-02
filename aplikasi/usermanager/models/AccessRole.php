<?php

namespace app\usermanager\models;

use Yii;
use yii\base\InlineAction;
use yii\web\Response;

/**
 * This is the model class for table "accessRole".
 *
 * @property integer $id
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


    /**
     * @param $action InlineAction
     * @param $rule
     * @return bool|Response
     */
    public static function getAccess($action, $rule)
    {
        if (Yii::$app->user->isGuest)
            return Yii::$app->response->redirect(['/site/login']);
        $action->controller->module->id;
//        print_r(Yii::$app->request->userIP);die;

        return true;
    }
}
