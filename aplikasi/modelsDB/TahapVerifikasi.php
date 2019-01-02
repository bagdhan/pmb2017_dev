<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "tahapVerifikasi".
 *
 * @property int $id
 * @property string $name
 * @property int $urutanMeja
 * @property string $pengaturanJson
 * @property string $deskripsi
 * @property string $dateCreate
 * @property string $dateUpdate
 *
 * @property Verifikasi[] $verifikasis
 */
class TahapVerifikasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tahapVerifikasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'deskripsi', 'dateCreate', 'dateUpdate'], 'required'],
            [['urutanMeja'], 'integer'],
            [['pengaturanJson', 'deskripsi'], 'string'],
            [['dateCreate', 'dateUpdate'], 'safe'],
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'urutanMeja' => Yii::t('app', 'Urutan Meja'),
            'pengaturanJson' => Yii::t('app', 'Pengaturan Json'),
            'deskripsi' => Yii::t('app', 'Deskripsi'),
            'dateCreate' => Yii::t('app', 'Date Create'),
            'dateUpdate' => Yii::t('app', 'Date Update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVerifikasis()
    {
        return $this->hasMany(Verifikasi::className(), ['tahapVerifikasi_id' => 'id']);
    }
}
