<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "departemen".
 *
 * @property int $id
 * @property string $kode
 * @property string $nama nama departemen
 * @property string $nama_en
 * @property string $inisial
 * @property int $fakultas_id
 *
 * @property Fakultas $fakultas
 * @property ProgramStudi[] $programStudis
 * @property UserHasDepartemen[] $userHasDepartemens
 * @property User[] $users
 */
class Departemen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'departemen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama', 'fakultas_id'], 'required'],
            [['fakultas_id'], 'integer'],
            [['kode'], 'string', 'max' => 3],
            [['nama', 'nama_en'], 'string', 'max' => 100],
            [['inisial'], 'string', 'max' => 10],
            [['fakultas_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fakultas::className(), 'targetAttribute' => ['fakultas_id' => 'id']],
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
            'fakultas_id' => Yii::t('app', 'Fakultas ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFakultas()
    {
        return $this->hasOne(Fakultas::className(), ['id' => 'fakultas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramStudis()
    {
        return $this->hasMany(ProgramStudi::className(), ['departemen_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasDepartemens()
    {
        return $this->hasMany(UserHasDepartemen::className(), ['departemen_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_has_departemen', ['departemen_id' => 'id']);
    }
}
