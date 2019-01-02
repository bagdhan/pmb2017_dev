<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "jenisPembiayan".
 *
 * @property int $id
 * @property string $title
 * @property string $deskripsi
 *
 * @property RencanaPembiayaan[] $rencanaPembiayaans
 */
class JenisPembiayan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenisPembiayan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deskripsi'], 'string'],
            [['title'], 'string', 'max' => 200],
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
            'deskripsi' => Yii::t('app', 'Deskripsi'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRencanaPembiayaans()
    {
        return $this->hasMany(RencanaPembiayaan::className(), ['jenisPembiayan_id' => 'id']);
    }
}
