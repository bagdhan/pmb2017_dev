<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "jenisSyaratTambahan".
 *
 * @property int $id
 * @property string $title
 * @property string $Deskripsi
 *
 * @property SyaratTambahan[] $syaratTambahans
 * @property SyaratTambahanHasManajemenJalurMasuk[] $syaratTambahanHasManajemenJalurMasuks
 */
class JenisSyaratTambahan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenisSyaratTambahan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Deskripsi'], 'string'],
            [['title'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'Deskripsi' => Yii::t('app', 'Deskripsi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSyaratTambahans()
    {
        return $this->hasMany(SyaratTambahan::className(), ['jenisSyaratTambahan_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSyaratTambahanHasManajemenJalurMasuks()
    {
        return $this->hasMany(SyaratTambahanHasManajemenJalurMasuk::className(), ['jenisSyaratTambahan_id' => 'id']);
    }
}
