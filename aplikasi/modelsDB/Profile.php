<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property int $user_id
 * @property string $dir
 * @property string $file
 * @property string $showName
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['dir'], 'string', 'max' => 200],
            [['file'], 'string', 'max' => 150],
            [['showName'], 'string', 'max' => 100],
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
            'dir' => Yii::t('app', 'Dir'),
            'file' => Yii::t('app', 'File'),
            'showName' => Yii::t('app', 'Show Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
