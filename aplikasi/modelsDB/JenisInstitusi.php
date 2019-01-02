<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "jenisInstitusi".
 *
 * @property int $id
 * @property string $jenis
 *
 * @property Institusi[] $institusis
 */
class JenisInstitusi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenisInstitusi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['jenis'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'jenis' => Yii::t('app', 'Jenis'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstitusis()
    {
        return $this->hasMany(Institusi::className(), ['jenisInstitusi_id' => 'id']);
    }
}
