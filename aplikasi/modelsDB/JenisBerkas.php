<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "jenisBerkas".
 *
 * @property int $id
 * @property int $strata
 * @property string $kode
 * @property string $nama
 * @property string $contohDokumen
 * @property string $deskripsi
 *
 * @property JenisBerkasHasJalurMasuk[] $jenisBerkasHasJalurMasuks
 * @property JalurMasuk[] $jalurMasuks
 * @property SyaratBerkas[] $syaratBerkas
 */
class JenisBerkas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenisBerkas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['strata'], 'integer'],
            [['deskripsi'], 'string'],
            [['kode'], 'string', 'max' => 45],
            [['nama'], 'string', 'max' => 100],
            [['contohDokumen'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'strata' => Yii::t('app', 'Strata'),
            'kode' => Yii::t('app', 'Kode'),
            'nama' => Yii::t('app', 'Nama'),
            'contohDokumen' => Yii::t('app', 'Contoh Dokumen'),
            'deskripsi' => Yii::t('app', 'Deskripsi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisBerkasHasJalurMasuks()
    {
        return $this->hasMany(JenisBerkasHasJalurMasuk::className(), ['jenisBerkas_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJalurMasuks()
    {
        return $this->hasMany(JalurMasuk::className(), ['id' => 'jalurMasuk_id'])->viaTable('jenisBerkas_has_jalurMasuk', ['jenisBerkas_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSyaratBerkas()
    {
        return $this->hasMany(SyaratBerkas::className(), ['jenisBerkas_id' => 'id']);
    }
}
