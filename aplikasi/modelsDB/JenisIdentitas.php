<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "jenisIdentitas".
 *
 * @property int $id
 * @property string $nama
 *
 * @property Identitas[] $entitas
 */
class JenisIdentitas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenisIdentitas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['nama'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nama' => Yii::t('app', 'Nama'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntitas()
    {
        return $this->hasMany(Identitas::className(), ['jenisIdentitas_id' => 'id']);
    }
}
