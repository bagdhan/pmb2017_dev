<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 4/26/2017
 * Time: 3:26 PM
 */

namespace app\modules\pleno\models;

use Yii;
use app\modelsDB\UserHasFakultas;
use app\modelsDB\UserHasDepartemen;
use app\modelsDB\UserHasProgramStudi;
use app\models\CostumDate;
use app\models\SecurityChack;

/**
 * Class ProsesSidang
 * @property string message
 * @property string actionBtn
 * @property string hasilSeleksi
 * @property ProsesSidang modelSeleksi
 * @property string psSeleksi
 * @property string ketSeleksi
 *
 * @package app\modules\pleno\models
 */
class ProsesSidang extends \app\modelsDB\ProsesSidang
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
                $this->dateCreate = date('Y-m-d H:i:s');
            }
            $this->historyUpdate .= "{u" . Yii::$app->user->id . "; $this->sidang_id; $this->hasilKeputusan_id; $this->keterangan; " . date('Y-m-d His') . "}";
            return SecurityChack::checkSaveModel($this);
        }
        return false;
    }

    public function getMessage()
    {
        return 'ddd';
    }

    public function getActionBtn()
    {
        $date = date('Y-m-d H:i:s');
        $out = '';
        $accessRule = Yii::$app->user->identity->accessRole_id;
        $disabled = '';
        if($accessRule > 3 && $accessRule < 7){
            $disabled = ($this->getLock(Yii::$app->user->identity->id,$accessRule) == 0) ? '' : 'disabled';
            $disabled = ($this->sidang->jenisSidang_id != 3 || $this->sidang->tanggalSidang >= $date)? 'disabled' : $disabled;
        }

        $urutanPs = $this->pendaftaranHasProgramStudi->urutan;
        if ($this->hasilKeputusan_id > 0) {
            $class = $this->hasilKeputusan->tempClass;
            $putusan = $this->hasilKeputusan->keputusan;
            return "<button data-id='$this->hasilKeputusan_id' class='label label-$class' data-action='labelstatus' 
                data-ps='$urutanPs' $disabled>$putusan</button>";
        }

        /** @var HasilKeputusan[] $hkep */
        $hkep = HasilKeputusan::find()->where(['jenisSidang_id' => $this->sidang->jenisSidang_id, 'aktif' => 1])->all();
        foreach ($hkep as $item) {
            $out .= "<span style='display: none;'>zzz</span>
                <button data-id='$item->id' class='btn btn-sm btn-$item->tempClass' data-action='status' 
                data-ps='$urutanPs' title='$item->keputusan' $disabled>$item->temp</button>";
        }
        return $out;
    }

    private function getLock($user_id,$accessRole_id)
    {
        
        switch ($accessRole_id){
                case 4 :
                    $dtakses = UserHasFakultas::findOne($user_id);
                    $data = $dtakses->lock_seleksi;
                    break;
                case 5 :
                    $dtakses = UserHasDepartemen::findOne($user_id);
                    $data = $dtakses->lock_seleksi;
                    break;
                case 6 :
                    $dtakses = UserHasProgramStudi::findOne($user_id);
                    $data = $dtakses->lock_seleksi;
                    break;
            }
            if($dtakses)
                   return $data;
                else
                   return 0;

            
    }

    /** @var  self */
    public $_modelSeleksi;

    public function getModelSeleksi()
    {
        if ($this->_modelSeleksi == null) {
            /** @var self $prosesSeleksi */
            $prosesSeleksi = self::find()
                ->innerJoinWith('sidang')
                ->where([
                    'jenisSidang_id' => 3,
                    'paketPendaftaran_id' => $this->sidang->paketPendaftaran_id,
                    'pendaftaran_has_programStudi_id' => $this->pendaftaran_has_programStudi_id,
                ])
                ->one();
            $this->_modelSeleksi = empty($prosesSeleksi) ? new self() : $prosesSeleksi;
        }

        return $this->_modelSeleksi;
    }

    public function getHasilSeleksi()
    {
        return $this->modelSeleksi->hasilKeputusan_id == null ? '' :
            "<span data-id='$this->hasilKeputusan_id' class='label label-" . $this->modelSeleksi->hasilKeputusan->tempClass .
            "' >" . $this->modelSeleksi->hasilKeputusan->keputusan . "</span>";
    }

    public function getKetSeleksi()
    {
        return $this->modelSeleksi->keterangan;
    }

    public function getPsSeleksi()
    {
        if ($this->modelSeleksi->hasilKeputusan_id == null)
            return '';
        return "PS: " . $this->modelSeleksi->pendaftaranHasProgramStudi->programStudi->inisial;
    }
}