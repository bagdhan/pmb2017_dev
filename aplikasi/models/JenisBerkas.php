<?php
/**
 * Created by sublimetext.
 * User: doni
 * Date: 1/25/2017
 * Time: 2:14 PM
 */


namespace app\models;

use app\modelsDB\JalurMasuk;


/**
 * Class JenisBerkas
 * @package app\models
 */
class JenisBerkas extends \app\modelsDB\JenisBerkas
{
    public function getListBerkas($pendaftaran){
        $idjalur = Pendaftaran::findOne($pendaftaran)->paketPendaftaran->manajemenJalurMasuk->jalurMasuk_id;
        $syarat = JalurMasuk::findOne($idjalur)->jenisBerkas;
        if(isset($syarat)){
            return $syarat;
        }else{
            return false;
        }
        
    }
    
}