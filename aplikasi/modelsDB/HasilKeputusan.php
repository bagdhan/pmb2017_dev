<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "hasilKeputusan".
 *
 * @property int $id
 * @property string $keputusan
 * @property int $jenisSidang_id
 * @property int $aktif
 * @property string $temp
 * @property string $tempClass
 *
 * @property JenisSidang $jenisSidang
 * @property ProsesSidang[] $prosesSidangs
 */
class HasilKeputusan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hasilKeputusan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jenisSidang_id', 'aktif'], 'integer'],
            [['keputusan'], 'string', 'max' => 200],
            [['temp', 'tempClass'], 'string', 'max' => 45],
            [['jenisSidang_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisSidang::className(), 'targetAttribute' => ['jenisSidang_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'keputusan' => Yii::t('app', 'Keputusan'),
            'jenisSidang_id' => Yii::t('app', 'Jenis Sidang ID'),
            'aktif' => Yii::t('app', 'Aktif'),
            'temp' => Yii::t('app', 'Temp'),
            'tempClass' => Yii::t('app', 'Temp Class'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisSidang()
    {
        return $this->hasOne(JenisSidang::className(), ['id' => 'jenisSidang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProsesSidangs()
    {
        return $this->hasMany(ProsesSidang::className(), ['hasilKeputusan_id' => 'id']);
    }
}
