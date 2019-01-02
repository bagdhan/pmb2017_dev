<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/27/2017
 * Time: 7:20 AM
 */

namespace app\modules\pendaftaran\models;


use app\modelsDB\PinReference;

class PinVerifikasi extends \app\modelsDB\PinVerifikasi
{
    public static function getPin($noPendaftaran)
    {
        $modelPin = static::findOne(['noPendaftaran' => $noPendaftaran]);
        if (empty($modelPin)) {
            $modelPin = new self();
            $modelPin->noPendaftaran = $noPendaftaran;
//            $modelPin->pin = sprintf('%06d', mt_rand(0,999999)) . sprintf('%06d', mt_rand(0,999999));
            $modelPin->pin = PinReference::findOne(['noPendaftaran' => $noPendaftaran])->pin;
        }
        return $modelPin->save() ? $modelPin : null;
    }
}