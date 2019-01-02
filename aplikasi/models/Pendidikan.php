<?php
/**
 * Created by
 * User: Wisard17
 * Date: 10/02/2018
 * Time: 07.48 AM
 */

namespace app\models;


/**
 * Class Pendidikan
 * @package app\models
 *
 * @property int $alamatId
 * @property null|string $alamat
 * @property mixed $kab
 * @property mixed $prov
 * @property mixed $kec
 * @property mixed $des
 * @property mixed $jalan
 * @property mixed $namaUniversitas
 * @property mixed $negara
 * @property string $kotaAlamat
 * @property string $negaraText
 * @property Institusi $institusi
 */
class Pendidikan extends \app\modelsDB\Pendidikan
{

    public $_namaUniversitas;

    public $idPT;

    public $_jalan;
    public $_kec;
    public $_kab;
    public $_prov;
    public $_des;

    public $_negara;


    public $_alamatId;

    /**
     * @return mixed
     */
    public function getAlamatId()
    {
        if ($this->_alamatId == null)
            $this->_alamatId = $this->institusi == null ? null : $this->institusi->alamatId;
        return $this->_alamatId;
    }

    /**
     * @param mixed $alamatId
     */
    public function setAlamatId($alamatId)
    {
        $this->_alamatId = $alamatId;
    }

    public function getAlamat()
    {
        return $this->institusi == null ? null : ($this->institusi->alamatId == null ? null : '');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstitusi()
    {
        return $this->hasOne(Institusi::className(), ['id' => 'institusi_id']);
    }

    /**
     * @return string
     */
    public function getKotaAlamat()
    {
        return $this->institusi == null ? '' : ($this->institusi->alamat == null ? null :
            ($this->institusi->alamat->desaKelurahan_kode == null ? '' :
                $this->institusi->alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->namaID . ', ' .
                $this->institusi->alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsiKode->namaID));
    }

    /**
     * @return string
     */
    public function getNegaraText()
    {
        return $this->institusi == null ? '' : ($this->institusi->negara == null ? '' : $this->institusi->negara->nama);
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        if (parent::save($runValidation, $attributeNames)) {
            if ($this->_alamatId != null && $this->institusi_id != null) {
                $lk = $this->institusi;
                $lk->alamatId = $this->_alamatId;
                $lk->save();
            }
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getJalan()
    {
        return $this->institusi == null ? '' : ($this->institusi->alamat == null ? null : $this->institusi->alamat->jalan);
    }

    /**
     * @param mixed $jalan
     */
    public function setJalan($jalan)
    {
        $this->_jalan = $jalan;
    }

    /**
     * @return mixed
     */
    public function getKec()
    {
        return $this->institusi == null ? '' : ($this->institusi->alamat == null ? null :
            ($this->institusi->alamat->desaKelurahan_kode == '' ? '' :
            $this->institusi->alamat->desaKelurahanKode->kecamatan_kode));
    }

    /**
     * @return mixed
     */
    public function getKab()
    {
        return $this->institusi == null ? '' : ($this->institusi->alamat == null ? null :
            ($this->institusi->alamat->desaKelurahan_kode == '' ? '' :
            $this->institusi->alamat->desaKelurahanKode->kecamatanKode->kabupatenKota_kode));
    }

    /**
     * @return mixed
     */
    public function getProv()
    {
        return $this->institusi == null ? '' : ($this->institusi->alamat == null ? null :
            ($this->institusi->alamat->desaKelurahan_kode == null ? '' :
            $this->institusi->alamat->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsi_kode));
    }

    /**
     * @return mixed
     */
    public function getDes()
    {
        return $this->institusi == null ? '' : ($this->institusi->alamat == null ? null :
            ($this->institusi->alamat->desaKelurahan_kode == '' ? '' :
                $this->institusi->alamat->desaKelurahan_kode));
    }

    /**
     * @param mixed $des
     */
    public function setDes($des)
    {
        $this->_des = $des;
    }

    /**
     * @param mixed $kec
     */
    public function setKec($kec)
    {
        $this->_kec = $kec;
    }

    /**
     * @param mixed $kab
     */
    public function setKab($kab)
    {
        $this->_kab = $kab;
    }

    /**
     * @param mixed $prov
     */
    public function setProv($prov)
    {
        $this->_prov = $prov;
    }

    /**
     * @return mixed
     */
    public function getNamaUniversitas()
    {
        if ($this->_namaUniversitas == null)
            $this->_namaUniversitas = $this->institusi == null ? '' : $this->institusi->nama;
        return $this->_namaUniversitas;
    }

    /**
     * @param mixed $namaUniversitas
     */
    public function setNamaUniversitas($namaUniversitas)
    {
        $this->_namaUniversitas = $namaUniversitas;
    }

    /**
     * @return mixed
     */
    public function getNegara()
    {
        return $this->_negara;
    }

    /**
     * @param mixed $negara
     */
    public function setNegara($negara)
    {
        $this->_negara = $negara;
    }
}