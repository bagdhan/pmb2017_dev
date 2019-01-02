<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "rencanaPembiayaan".
 *
 * @property int $id
 * @property int $jenisPembiayan_id
 * @property string $deskripsi
 *
 * @property Pendaftaran[] $pendaftarans
 * @property JenisPembiayan $jenisPembiayan
 */
class RencanaPembiayaan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rencanaPembiayaan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jenisPembiayan_id'], 'integer'],
            [['deskripsi'], 'string'],
            [['jenisPembiayan_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisPembiayan::className(), 'targetAttribute' => ['jenisPembiayan_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'jenisPembiayan_id' => Yii::t('app', 'Jenis Pembiayan ID'),
            'deskripsi' => Yii::t('app', 'Deskripsi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendaftarans()
    {
        return $this->hasMany(Pendaftaran::className(), ['rencanaPembiayaan_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisPembiayan()
    {
        return $this->hasOne(JenisPembiayan::className(), ['id' => 'jenisPembiayan_id']);
    }
}
