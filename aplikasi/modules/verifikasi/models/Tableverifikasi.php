<?php

namespace app\modules\verifikasi\models;

use app\modelsDB\TahapVerifikasi;
use Yii;

/**
 * This is the model class for table "tbl_tableverifikasi".
 *
 * @property integer $id
 * @property string $name
 * @property integer $urutanMeja
 * @property string $pengaturanJson
 * @property string $deskripsi
 * @property string $dateCreate
 * @property string $dateUpdate
 */
class Tableverifikasi extends TahapVerifikasi
{

    public function beforeSave($insert)
    {
        date_default_timezone_set("Asia/Jakarta");
        if (parent::beforeSave($insert)) {
            $data = $this->findOne($this->id);
            if(empty($data)){
                $this->dateCreate=date('Y-m-d H:i:s');
                $this->dateUpdate=date('Y-m-d H:i:s');
            }
            else{
                $this->dateUpdate=date('Y-m-d H:i:s');
            }
            return true;
        } else {
            return false;
        }
    }
}
