<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "syaratTambahan".
 *
 * @property int $id
 * @property string $score
 * @property string $organizer
 * @property string $dateExercise
 * @property string $dateExpired
 * @property int $jenisSyaratTambahan_id
 * @property string $pendaftaran_noPendaftaran
 *
 * @property JenisSyaratTambahan $jenisSyaratTambahan
 * @property Pendaftaran $pendaftaranNoPendaftaran
 */
class SyaratTambahan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'syaratTambahan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dateExercise', 'dateExpired'], 'safe'],
            [['jenisSyaratTambahan_id', 'pendaftaran_noPendaftaran'], 'required'],
            [['jenisSyaratTambahan_id'], 'integer'],
            [['score'], 'string', 'max' => 45],
            [['organizer', 'pendaftaran_noPendaftaran'], 'string', 'max' => 100],
            [['jenisSyaratTambahan_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisSyaratTambahan::className(), 'targetAttribute' => ['jenisSyaratTambahan_id' => 'id']],
            [['pendaftaran_noPendaftaran'], 'exist', 'skipOnError' => true, 'targetClass' => Pendaftaran::className(), 'targetAttribute' => ['pendaftaran_noPendaftaran' => 'noPendaftaran']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'score' => Yii::t('app', 'Score'),
            'organizer' => Yii::t('app', 'Organizer'),
            'dateExercise' => Yii::t('app', 'Date Exercise'),
            'dateExpired' => Yii::t('app', 'Date Expired'),
            'jenisSyaratTambahan_id' => Yii::t('app', 'Jenis Syarat Tambahan ID'),
            'pendaftaran_noPendaftaran' => Yii::t('app', 'Pendaftaran No Pendaftaran'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisSyaratTambahan()
    {
        return $this->hasOne(JenisSyaratTambahan::className(), ['id' => 'jenisSyaratTambahan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendaftaranNoPendaftaran()
    {
        return $this->hasOne(Pendaftaran::className(), ['noPendaftaran' => 'pendaftaran_noPendaftaran']);
    }
}
