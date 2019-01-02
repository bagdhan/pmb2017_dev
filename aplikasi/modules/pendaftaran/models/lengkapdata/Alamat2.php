<?php
/**
 * Created by PhpStorm.
 * User: Wisard17
 * Date: 10/02/2018
 * Time: 05.13 AM
 */

namespace app\modules\pendaftaran\models\lengkapdata;


class Alamat2 extends Alamat
{

    public $st = 2;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jalan', 'kodePos'], 'required'],
            [['rt', 'rw', 'desaKelurahan_kode'], 'string', 'max' => 10],
            [['kodePos'], 'string', 'max' => 45],

        ];
    }
}