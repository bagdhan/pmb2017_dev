<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 2/25/2017
 * Time: 4:08 PM
 */

namespace app\modules\pendaftaran\models;


use app\modules\pendaftaran\models\lengkapdata\Alamat;
use app\modelsDB\InstansiHasAlamat;
use app\modelsDB\PekerjaanHasInstansi;
use yii\helpers\ArrayHelper;

/**
 * Class Instansi
 * @property Alamat alamat
 * @package app\modules\pendaftaran\models
 *
 */
class Instansi extends \app\modelsDB\Instansi
{

    public $jalan;
    public $kodePos;
    public $rt;
    public $rw;

    public $kec;
    public $kab;
    public $prov;
    public $des;


    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['kec', 'kab', 'prov','jalan', 'kodePos', 'rt', 'rw'], 'string'],
        ]);
    }

    /**
     * @param $idPekerjaan
     * @return Instansi|static
     */
    public static function findOn($idPekerjaan)
    {
        $conectinstansi = PekerjaanHasInstansi::findOne(['pekerjaan_id' => $idPekerjaan]);
        if (empty($conectinstansi)) {
            $instansi = new self();
        } else {
            $instansi = self::findOne($conectinstansi->instansi_id);
        }
        return $instansi;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {


            return true;
        }
        return false;
    }

    public function saveData($idPekerjaan)
    {
        $conectinstansi = PekerjaanHasInstansi::findOne(['pekerjaan_id' => $idPekerjaan]);
        if (empty($conectinstansi)) {
            $this->save(false);
            $conectinstansi = new PekerjaanHasInstansi();
            $conectinstansi->pekerjaan_id = $idPekerjaan;
        } else {
            $this->save(false);
        }
        $conectinstansi->instansi_id = $this->id;

        $c_alamat = InstansiHasAlamat::findOne(['instansi_id' => $this->id]);
        if (empty($c_alamat)) {
            $c_alamat = new InstansiHasAlamat();
            $c_alamat->instansi_id = $this->id;
            $alamat = new Alamat();
        } else {
            $alamat = Alamat::findOne($c_alamat->alamat_id);
        }
        $alamat->jalan = $this->jalan;
        $alamat->desaKelurahan_kode = $this->des == '' ? null : $this->des;
        $alamat->save(false);

        $c_alamat->alamat_id = $alamat->id;
        $c_alamat->save();


        return $conectinstansi->save();
    }

    /**
     * @return mixed
     */
    public function getJalan()
    {
        return $this->jalan;
    }

    /**
     * @param mixed $jalan
     */
    public function setJalan($jalan)
    {
        $this->jalan = $jalan;
    }

    public function getAlamat()
    {
        $c_alamat = InstansiHasAlamat::findOne(['instansi_id' => $this->id]);
        if (empty($c_alamat)) {
            return new Alamat();
        } else {
            return Alamat::findOne($c_alamat->alamat_id);
        }
    }

}