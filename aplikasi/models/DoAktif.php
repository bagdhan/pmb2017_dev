<?php
/**
 * Created by PhpStorm.
 * User: doni46
 * Date: 5/12/2017
 * Time: 9:48 AM
 */

namespace app\models;


class DoAktif extends \app\modelsDB\DoAktif
{

	public static function getDataDoAktif($nama,$tanggal,$tempat,$status)
    {
        $model = DoAktif::find();
        $where = '(nama like "%'.$nama.'%" and tanggal_lahir = "'.$tanggal.'") or (tempat_lahir like "%'.$tempat.'%" and tanggal_lahir = "'.$tanggal.'") or (nama like "%'.$nama.'%")';
        $data = $model->where($where)->andWhere(['status'=>$status])->all();
        
        
        return $data;
    }

}