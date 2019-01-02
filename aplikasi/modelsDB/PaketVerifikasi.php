<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "paketVerifikasi".
 *
 * @property int $id
 * @property string $Name
 * @property int $aktiv
 *
 * @property PaketPendaftaranHasPaketVerifikasi[] $paketPendaftaranHasPaketVerifikasis
 * @property PaketPendaftaran[] $paketPendaftarans
 */
class PaketVerifikasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paketVerifikasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['aktiv'], 'integer'],
            [['Name'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'Name' => Yii::t('app', 'Name'),
            'aktiv' => Yii::t('app', 'Aktiv'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaketPendaftaranHasPaketVerifikasis()
    {
        return $this->hasMany(PaketPendaftaranHasPaketVerifikasi::className(), ['paketVerifikasi_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaketPendaftarans()
    {
        return $this->hasMany(PaketPendaftaran::className(), ['id' => 'paketPendaftaran_id'])->viaTable('paketPendaftaran_has_paketVerifikasi', ['paketVerifikasi_id' => 'id']);
    }
}
