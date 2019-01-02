<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "doAktif".
 *
 * @property string $nim
 * @property string $nama
 * @property string $tempat_lahir
 * @property string $tanggal_lahir
 * @property string $tahun_masuk
 * @property string $program_studi
 * @property string $strata
 * @property int $status
 */
class DoAktif extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'doAktif';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nim'], 'required'],
            [['tanggal_lahir', 'tahun_masuk'], 'safe'],
            [['status'], 'integer'],
            [['nim', 'program_studi'], 'string', 'max' => 15],
            [['nama'], 'string', 'max' => 200],
            [['tempat_lahir'], 'string', 'max' => 50],
            [['strata'], 'string', 'max' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nim' => Yii::t('app', 'Nim'),
            'nama' => Yii::t('app', 'Nama'),
            'tempat_lahir' => Yii::t('app', 'Tempat Lahir'),
            'tanggal_lahir' => Yii::t('app', 'Tanggal Lahir'),
            'tahun_masuk' => Yii::t('app', 'Tahun Masuk'),
            'program_studi' => Yii::t('app', 'Program Studi'),
            'strata' => Yii::t('app', 'Strata'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
