<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "jenisSidang".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 *
 * @property HasilKeputusan[] $hasilKeputusans
 * @property Sidang[] $sidangs
 */
class JenisSidang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenisSidang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['title'], 'string', 'max' => 100],
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
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHasilKeputusans()
    {
        return $this->hasMany(HasilKeputusan::className(), ['jenisSidang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSidangs()
    {
        return $this->hasMany(Sidang::className(), ['jenisSidang_id' => 'id']);
    }
}
