<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "jenisKerjasama".
 *
 * @property int $id
 * @property string $nama
 * @property string $deskripsi
 * @property int $show
 *
 * @property Kerjasama[] $kerjasamas
 */
class JenisKerjasama extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenisKerjasama';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deskripsi'], 'string'],
            [['show'], 'integer'],
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
            'deskripsi' => Yii::t('app', 'Deskripsi'),
            'show' => Yii::t('app', 'Show'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKerjasamas()
    {
        return $this->hasMany(Kerjasama::className(), ['jenisKerjasama_id' => 'id']);
    }
}
