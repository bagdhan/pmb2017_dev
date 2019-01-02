<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 2/5/2017
 * Time: 11:28 PM
 */

namespace app\models;

use Yii;

/**
 * @property string dirBerkas
 * @property string urlBerkas
 */
class SyaratBerkas extends \app\modelsDB\SyaratBerkas
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

            return true;
        }
        return false;
    }

    public function getDirBerkas()
    {
        $dir = Yii::getAlias('@arsipdir') . '/' . $this->pendaftaran_noPendaftaran . '/';
        if (!file_exists(Yii::getAlias('@arsipdir'))) {
            mkdir(Yii::getAlias('@arsipdir'), 0777, true);
        }
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir;
    }

    public function getUrlBerkas()
    {
        Yii::$app->assetManager->publish($this->dirBerkas);
        return Yii::$app->assetManager->getPublishedUrl($this->dirBerkas);
    }
}