<?php

namespace app\modules\verifikasi\models;

use app\models\ModelData;
use app\models\ProgramStudi;
use app\modelsDB\PaketVerifikasi;
use app\modules\pendaftaran\models\lengkapdata\DataPribadi;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class Verifikasi
 * @package app\modules\verifikasi\models
 *
 * @property string $ogActivity
 * @property string $name
 * @property string $noPendaftaran
 * @property string pspilihan
 * @property ProgramStudi prodi
 * @property string inisial
 * @property string strata
 * @property string logActivity
 * @property string tahap
 * @property string meja
 */
class Verifikasi extends \app\modelsDB\Verifikasi
{
    public function getNoPendaftaran()
    {
        return $this->pendaftaran_noPendaftaran;
    }

    public function getMeja()
    {
        return $this->tahapVerifikasi == null ? 'tidak ada meja' : $this->tahapVerifikasi->name;
    }

    public function getTahap()
    {
        return $this->paketVerifikasi->Name . '/' . $this->noAntrian;
    }

    public function getName()
    {
        return $this->pendaftaranNoPendaftaran->orang->nama;
    }

    public function getStrata()
    {
        return 'S' . ($this->pendaftaranNoPendaftaran->manajemenJalurMasuk != null ?
            $this->pendaftaranNoPendaftaran->manajemenJalurMasuk->strata : 2);
    }

    public function getInisial()
    {
        return $this->prodi != null ? $this->prodi->inisial : '';
    }

    public $_prodi;

    public $_urutanprodi = 0 ;

    public function getProdi()
    {
        if ($this->_prodi == null) {
            foreach ($this->pendaftaranNoPendaftaran->pendaftaranHasProgramStudis as $programStudi) {
                foreach ($programStudi->prosesSidangs as $sidang) {
                    if (in_array($sidang->hasilKeputusan_id, [4,5])) {
                        $this->_urutanprodi = $programStudi->urutan;
                        $this->_prodi = $programStudi->programStudi;
                    }
                }
            }
        }
        return $this->_prodi;
    }

    public function getPspilihan()
    {

        return $this->prodi != null ? $this->prodi->kode : '';
    }


    public function getLogActivity()
    {
        $tbl = "<table class='table'>
                    <tr>
                        <th>No</th>
                        <th>Posisi</th>
                        <th>Date</th>
                    </tr>";

        foreach (Json::decode($this->log) as $i => $log) {
            $posisi = isset($log['posisi']) ? $log['posisi'] : '';
            $datem = isset($log['dateMasuk']) ? $log['dateMasuk'] : '';
            $tbl .= "<tr>
                        <td>$i</td>
                        <td>$posisi</td>
                        <td>$datem</td>
                    </tr>";
        }
        $tbl .= "</table>";
        return $tbl;
    }


    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {
            $data = static::findOne(['pendaftaran_noPendaftaran' => $this->pendaftaran_noPendaftaran]);
            if (empty($data)) {
                $this->noAntrian = $this->genNoAntrian($this->paketVerifikasi_id);
                $this->dateCreate = date('Y-m-d H:i:s');
            }
            return true;
        } else {
            return false;
        }
    }

    public function genNoAntrian($tahap)
    {
        $no = (int)$this->find()->where(['paketVerifikasi_id' => $tahap])->max('noAntrian') + 1;
        $data = static::findOne(['paketVerifikasi_id' => $tahap, 'noAntrian' => $no]);
        if (empty($data))
            return $no;
        return $this->genNoAntrian($tahap);
    }

    public static function getAntrian($filter = '')
    {
        $db = \Yii::$app->db;
        $sql = "select distinct
                    verif.*,
                    pop.nama,
                    pop.strata,
                    pop.mayor1,
                    pop.mayor2,
                    p1.inisial as kprodi1,
                    p2.inisial as kprodi2,
                    p1.mayor as prodi1,
                    p2.mayor as prodi2,
                    p1.kode,
                    p2.kode as kode2
                    
                 from tbl_verifikasi as verif
                 inner join po_pendaftaran as pop on pop.nopendaftaran=verif.noPendaftaran
                 inner join pin on pin.nopendaftaran=verif.noPendaftaran
                 inner join or_account on or_account.nopendaftaran=verif.noPendaftaran
                 left join po_mayor as p1 on pop.mayor1 = p1.kode
                 left join po_mayor as p2 on pop.mayor2 = p2.kode
                where pin.verifikasi =1
                $filter
                order by verif.noantrian;";
        $command = $db->createCommand($sql);
        $data = $command->queryAll();
        foreach ($data as $i => $d)
            $data[$i]['nama'] = ModelData::upfistarray($d['nama']);
        return $data;
    }

    public static function getAllverif($filter = '')
    {
//        $db = \Yii::$app->db;
//        $sql = "select distinct
//                    pop.nopendaftaran,
//                    pop.nama,
//                    pop.strata,
//                    pop.mayor1,
//                    pop.mayor2,
//                    p1.inisial as kprodi1,
//                    p2.inisial as kprodi2,
//                    p1.mayor as prodi1,
//                    p2.mayor as prodi2,
//                    p1.kode,
//                    p2.kode as kode2
//                 from po_pendaftaran as pop
//                 inner join pin on pin.nopendaftaran=pop.nopendaftaran
//                 inner join or_account on or_account.nopendaftaran=pop.nopendaftaran
//
//                 inner join tbl_pleno2 on tbl_pleno2.noPendaftaran=pop.noPendaftaran
//                 left join po_mayor as p1 on pop.mayor1 = p1.kode
//                 left join po_mayor as p2 on pop.mayor2 = p2.kode
//
//                 where pin.verifikasi =1 and tbl_pleno2.idHasilPleno <> 3
//                $filter
//
//                order by pop.nopendaftaran;";
//        $command = $db->createCommand($sql);
//        $data = $command->queryAll();
//        foreach ($data as $i => $d)
//            $data[$i]['nama'] = \app\models\ModelData::upfistarray($d['nama']);
        $data = Pendaftaran::allVerif();

        return $data;
    }

    public static function getData($mejaId, $filter = '')
    {
        if (in_array($mejaId, [1,2])) {
            $verif = self::find()->where("tahapVerifikasi_id = 1 or tahapVerifikasi_id = 2")->all();
        } else {
            $verif = self::find()->where(['tahapVerifikasi_id' => $mejaId])->all();
        }
        $data = [];
        /** @var self[] $verif */
        foreach ($verif as $item) {
            $data[] = array_merge(ArrayHelper::toArray($item),[
                'noPendaftaran' => $item->pendaftaran_noPendaftaran,
                'tahap' => $item->paketVerifikasi->Name,
                'pspilihan' => $item->pspilihan,
                'nama' => $item->name,
                'inisial' => $item->inisial,
                'strata' => $item->strata,
                'noantrian' => $item->noAntrian,

            ]);
        }

        return $data;
    }


    /**
     * @param $nopen
     * @param string $filter
     * @return object|self
     */
    public static function getOneData($nopen)
    {
//        $data =
        return (object)['nama' => ''];
//        return (object)$data[0];
    }
}
