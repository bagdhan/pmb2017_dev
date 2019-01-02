<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "jalurMasuk".
 *
 * @property int $id
 * @property string $nama
 * @property string $deskripsi
 *
 * @property JenisBerkasHasJalurMasuk[] $jenisBerkasHasJalurMasuks
 * @property JenisBerkas[] $jenisBerkas
 * @property ManajemenJalurMasuk[] $manajemenJalurMasuks
 */
class JalurMasuk extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jalurMasuk';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deskripsi'], 'string'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisBerkasHasJalurMasuks()
    {
        return $this->hasMany(JenisBerkasHasJalurMasuk::className(), ['jalurMasuk_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisBerkas()
    {
        return $this->hasMany(JenisBerkas::className(), ['id' => 'jenisBerkas_id'])->viaTable('jenisBerkas_has_jalurMasuk', ['jalurMasuk_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManajemenJalurMasuks()
    {
        return $this->hasMany(ManajemenJalurMasuk::className(), ['jalurMasuk_id' => 'id']);
    }
}
