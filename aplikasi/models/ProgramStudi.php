<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 2/21/2017
 * Time: 2:44 PM
 */

namespace app\models;


class ProgramStudi extends \app\modelsDB\ProgramStudi
{
    public function getFakultasId()
    {
        return $this->departemen->fakultas_id;
    }

    public function getDepartemenId()
    {
        return $this->departemen_id;
    }

    public static function getTblMayor($sql='',$jalurMasuk)
    {
        $_POST['jalurMasuk'] = $jalurMasuk;
        $data = Self::find()->joinWith(['manajemenJalurMasuks m'=> function($q){$q->where(['m.jalurMasuk_id'=>$_POST['jalurMasuk']]);}])->where($sql)->all();
        return $data;
    }
}