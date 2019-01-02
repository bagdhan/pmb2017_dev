<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "statusForm".
 *
 * @property int $id
 * @property string $noPendaftaran
 * @property int $form1
 * @property int $form2
 * @property int $form3
 * @property int $form4
 * @property int $form5
 * @property int $form6
 * @property int $form7
 * @property int $form8
 * @property int $form9
 * @property int $form10
 * @property int $form11
 * @property int $form12
 *
 * @property Pendaftaran $noPendaftaran0
 */
class StatusForm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'statusForm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['noPendaftaran'], 'required'],
            [['form1', 'form2', 'form3', 'form4', 'form5', 'form6', 'form7', 'form8', 'form9', 'form10', 'form11', 'form12'], 'integer'],
            [['noPendaftaran'], 'string', 'max' => 100],
            [['noPendaftaran'], 'exist', 'skipOnError' => true, 'targetClass' => Pendaftaran::className(), 'targetAttribute' => ['noPendaftaran' => 'noPendaftaran']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'noPendaftaran' => Yii::t('app', 'No Pendaftaran'),
            'form1' => Yii::t('app', 'Form1'),
            'form2' => Yii::t('app', 'Form2'),
            'form3' => Yii::t('app', 'Form3'),
            'form4' => Yii::t('app', 'Form4'),
            'form5' => Yii::t('app', 'Form5'),
            'form6' => Yii::t('app', 'Form6'),
            'form7' => Yii::t('app', 'Form7'),
            'form8' => Yii::t('app', 'Form8'),
            'form9' => Yii::t('app', 'Form9'),
            'form10' => Yii::t('app', 'Form10'),
            'form11' => Yii::t('app', 'Form11'),
            'form12' => Yii::t('app', 'Form12'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoPendaftaran0()
    {
        return $this->hasOne(Pendaftaran::className(), ['noPendaftaran' => 'noPendaftaran']);
    }
}
