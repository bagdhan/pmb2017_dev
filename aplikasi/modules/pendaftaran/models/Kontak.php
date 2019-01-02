<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/19/2017
 * Time: 10:24 AM
 */

namespace app\modules\pendaftaran\models;


use app\models\CostumDate;

class Kontak extends \app\modelsDB\Kontak
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
}