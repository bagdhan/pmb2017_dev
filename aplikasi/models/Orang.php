<?php
/**
 * Created by
 * User: wisard17
 * Date: 1/17/2017
 * Time: 2:50 PM
 */

namespace app\models;

use Yii;


/**
 * Class Orang
 * @package app\models
 *
 * @property \yii\db\ActiveQuery|\app\models\Pendaftaran $pendaftaran
 * @property string $photo
 */
class Orang extends \app\modelsDB\Orang
{
    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        CostumDate::tiemZone();
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->waktuBuat = date('Y-m-d H:i:s');
                $this->waktuUbah = date('Y-m-d H:i:s');
            } else {
                $this->waktuUbah = date('Y-m-d H:i:s');
            }
            $this->tanggalLahir = $this->tanggalLahir == null ? null : date('Y-m-d H:i:s', strtotime($this->tanggalLahir));
            $this->nama = self::upfistarray($this->nama);
            return true;
        }
        return false;
    }

     public function getPhoto()
    {
        $noPendaftaran = isset(Yii::$app->user->identity->orang->pendaftarans[0]->noPendaftaran)? Yii::$app->user->identity->orang->pendaftarans[0]->noPendaftaran : '';
        $dir = Yii::getAlias('@arsipdir'). '/'.$noPendaftaran;

        if (file_exists($dir . '/foto_profile.jpg')) {
            Yii::$app->assetManager->publish($dir);
            $directoryAsset = Yii::$app->assetManager->getPublishedUrl($dir) . '/foto_profile.jpg';
        } else {
            $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@app/adminlte/dist/') . '/img/user.jpg';
        }

        return $directoryAsset;
    }

    public static function upfistarray($input){
        $pca = explode(' ', $input);
        $g='';
        foreach ($pca as $p) {
            if (strlen($p) > 3)
                $g[] = ucfirst(strtolower($p));
            else
                $g[] = $p;
        }
        return join(' ',$g);
    }

    /**
     * @return array
     */
    public static function getCountry()
    {
        $out = [];

        foreach (Negara::find()->orderBy('nama ASC')->all() as $negara) {
            $out[$negara->id] = $negara->nama . " ($negara->kode)";
        }

        return $out;
    }

    /**
     * @return \yii\db\ActiveQuery|Pendaftaran
     */
    public function getPendaftaran()
    {
        return $this->hasOne(Pendaftaran::className(), ['orang_id' => 'id']);
    }
}