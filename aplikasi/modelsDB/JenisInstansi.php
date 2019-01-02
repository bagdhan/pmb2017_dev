<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "jenisInstansi".
 *
 * @property int $id
 * @property string $nama
 *
 * @property Pekerjaan[] $pekerjaans
 */
class JenisInstansi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenisInstansi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama'], 'string', 'max' => 50],
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
    public function getPekerjaans()
    {
        return $this->hasMany(Pekerjaan::className(), ['jenisInstansi_id' => 'id']);
    }
}
