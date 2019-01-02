<?php
/**
 * Created by PhpStorm.
 * User: Wisard17
 * Date: 10/02/2018
 * Time: 07.51 AM
 */

namespace app\models;


use app\modelsDB\InstitusiHasAlamat;

/**
 *
 * @property \app\models\Alamat $alamat
 * @property int $alamatId
 */
class Institusi extends \app\modelsDB\Institusi
{
    public $_alamatId;

    public $_alamat;
    /**
     * @return mixed
     */
    public function getAlamatId()
    {
        if ($this->_alamatId == null)
            $this->_alamatId = sizeof($this->institusiHasAlamats) > 1 ? $this->institusiHasAlamats[0]->alamat_id : null;
        return $this->_alamatId;
    }

    /**
     * @return Alamat
     */
    public function getAlamat()
    {
        if ($this->_alamat == null)
            $this->_alamat = sizeof($this->institusiHasAlamats) > 1 ? $this->institusiHasAlamats[0]->alamat : null;
        return $this->_alamat;
    }

    /**
     * @param mixed $alamatId
     */
    public function setAlamatId($alamatId)
    {
        $this->_alamatId = $alamatId;
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        if (parent::save($runValidation, $attributeNames)) {
            if ($this->_alamatId != null) {
                $lk = sizeof($this->institusiHasAlamats) > 1 ?
                    $this->institusiHasAlamats[0] : new InstitusiHasAlamat(['institusi_id' => $this->id]);
                $lk->alamat_id = $this->_alamatId;
                $lk->save();
            }
            return true;
        }
        return false;
    }
}