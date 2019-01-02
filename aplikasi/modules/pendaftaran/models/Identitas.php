<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/19/2017
 * Time: 10:12 AM
 */

namespace app\modules\pendaftaran\models;


use app\models\CostumDate;
use app\models\SecurityChack;

class Identitas extends \app\modelsDB\Identitas
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

            return SecurityChack::checkSaveModel($this);
        }
        return false;
    }
}