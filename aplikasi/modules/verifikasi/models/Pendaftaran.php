<?php
/**
 * Created by PhpStorm.
 * User: Wisard17
 * Date: 8/18/2017
 * Time: 9:55 AM
 */

namespace app\modules\verifikasi\models;

use Yii;

/**
 * Class Pendaftaran
 * @property Verifikasi verifikasiBerkas
 * @property string name
 * @property int noAntrian
 * @property string pspilihan
 * @property string photo
 * @property string strata
 * @package app\modules\verifikasi\models
 */
class Pendaftaran extends \app\models\Pendaftaran
{

    public static function allVerif()
    {
        return [];
    }

    public function getName()
    {
        return $this->orang != null ? $this->orang->nama : '';
    }

    public function getNoAntrian()
    {
        return $this->verifikasiBerkas != null ? $this->verifikasiBerkas->noAntrian : 0;
    }

    public function getPspilihan()
    {
        $prodi = null;
        foreach ($this->pendaftaranHasProgramStudis as $programStudi) {
            foreach ($programStudi->prosesSidangs as $sidang) {
                if (in_array($sidang->hasilKeputusan_id, [4,5]))
                    $prodi = $programStudi->programStudi;
            }
        }

        return $prodi != null ? $prodi->inisial . '/' . $prodi->nama : '';
    }

    public function getStrata()
    {
        return 'S' . ($this->manajemenJalurMasuk != null ?
                $this->manajemenJalurMasuk->strata : 2);
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVerifikasiBerkas()
    {
        return $this->hasOne(Verifikasi::className(), ['pendaftaran_noPendaftaran' => 'noPendaftaran']);
    }

    public function getPhoto()
    {
        $dir = Yii::getAlias('@arsipdir'). '/' . $this->noPendaftaran ;

        if (file_exists($dir . '/foto_profile.jpg')) {
            Yii::$app->assetManager->publish($dir);
            $directoryAsset = Yii::$app->assetManager->getPublishedUrl($dir) . '/foto_profile.jpg';
        } else {
            $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@app/adminlte/dist/') . '/img/user.jpg';
        }

        return $directoryAsset;
    }

}