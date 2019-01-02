<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "fakultas".
 *
 * @property int $id ID Fakultas
 * @property string $kode Kode Fakultas A .. I
 * @property string $nama Nama fakultas dalam bahasa Indonesia
 * @property string $nama_en Nama fakultas dalam bahasa Inggris
 * @property string $inisial Inisial fakultas dalam bahasa indonesia
 * @property string $kode_nas_fak Kode Program Studi yang tercatat di Dikti
 * @property string $dekan nama dekan yang menjabat
 * @property string $nip_dekan
 * @property string $wadek_1 nama wakil dekan 1 yang menjabat
 * @property string $nip_wadek_1
 * @property string $wadek_2 nama wakil dekan 2 yang menjabat
 * @property string $nip_wadek_2
 *
 * @property Departemen[] $departemens
 * @property UserHasFakultas[] $userHasFakultas
 * @property User[] $users
 */
class Fakultas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fakultas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode', 'nama'], 'required'],
            [['kode'], 'string', 'max' => 1],
            [['nama', 'nama_en'], 'string', 'max' => 50],
            [['inisial'], 'string', 'max' => 9],
            [['kode_nas_fak'], 'string', 'max' => 20],
            [['dekan', 'wadek_1', 'wadek_2'], 'string', 'max' => 200],
            [['nip_dekan', 'nip_wadek_1', 'nip_wadek_2'], 'string', 'max' => 25],
            [['kode'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'kode' => Yii::t('app', 'Kode'),
            'nama' => Yii::t('app', 'Nama'),
            'nama_en' => Yii::t('app', 'Nama En'),
            'inisial' => Yii::t('app', 'Inisial'),
            'kode_nas_fak' => Yii::t('app', 'Kode Nas Fak'),
            'dekan' => Yii::t('app', 'Dekan'),
            'nip_dekan' => Yii::t('app', 'Nip Dekan'),
            'wadek_1' => Yii::t('app', 'Wadek 1'),
            'nip_wadek_1' => Yii::t('app', 'Nip Wadek 1'),
            'wadek_2' => Yii::t('app', 'Wadek 2'),
            'nip_wadek_2' => Yii::t('app', 'Nip Wadek 2'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartemens()
    {
        return $this->hasMany(Departemen::className(), ['fakultas_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasFakultas()
    {
        return $this->hasMany(UserHasFakultas::className(), ['fakultas_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_has_fakultas', ['fakultas_id' => 'id']);
    }
}
