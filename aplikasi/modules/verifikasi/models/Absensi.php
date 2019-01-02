<?php

namespace app\modules\verifikasi\models;

use app\models\ModelData;
use app\models\NrpGenerator;
use Yii;

/**
 * This is the model class for table "tbl_absensi".
 *
 * @property integer $id
 * @property string $identity
 * @property string $even
 * @property string $dateCreate
 * @property string $dateUpdate
 *
 * @property string $name
 * @property string $nrp
 * @property string $ps
 * @property string $gender
 * @property string $sps
 */
class Absensi extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_absensi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identity', 'even'], 'required'],
            [['dateCreate', 'dateUpdate'], 'safe'],
            [['identity'], 'string', 'max' => 150],
            [['even'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'identity' => Yii::t('app', 'Identity'),
            'even' => Yii::t('app', 'Even'),
            'dateCreate' => Yii::t('app', 'Date Create'),
            'dateUpdate' => Yii::t('app', 'Date Update'),
        ];
    }

    public function getName()
    {
        $noPen = $this->identity;
        return ModelData::getName($noPen) == null ? ModelData::getName($noPen) : '';
    }

    public function getNrp()
    {
        $noPen = $this->identity;
        $nrp = new NrpGenerator();
        return $nrp->getNrp($noPen);
    }

    public function getPs()
    {
        $noPen = $this->identity;

        $data = self::getTblMayor($noPen);
        return $data->pspilihan;
    }

    public function getSps()
    {
        $noPen = $this->identity;

        $data = self::getTblMayor($noPen);
        return $data->inisial;
    }

    public static function getTblMayor($nopen)
    {
        $db = \Yii::$app->db;
        $sql = "select distinct
                                    
                    pop.nama,
                    pop.tempatlahir,
                    pop.tanggallahir,
                    or_account.strata,
                    ps.inisial,
                    ps.mayor as pspilihan
                                     
                 from po_pendaftaran as pop
                 
                 inner join pin on pin.nopendaftaran=pop.nopendaftaran
                 inner join or_account on or_account.nopendaftaran=pop.nopendaftaran
                 inner join tbl_pleno2 on tbl_pleno2.noPendaftaran=pop.nopendaftaran
                 left join po_mayor as ps on tbl_pleno2.psPilihan = ps.kode
                where pin.verifikasi = 1 and tbl_pleno2.idHasilPleno <> 3 and pop.nopendaftaran = '$nopen' ";
        $command = $db->createCommand($sql);

        $data = $command->queryAll();
        if(sizeof($data) < 1)
            return (object)['nama'=>''];
        return (object)$data[0];
    }

    public static function getOneData($nopen, $filter = '')
    {
        $db = \Yii::$app->db;

        $sql = "select distinct
                    
                 --   verif.id as noantrian,
                    tbl_pleno2.*,
                    pop.*,
                    or_account.strata,
                    ps.inisial,
                    ps.mayor as pspilihan
                                     
                 from po_pendaftaran as pop
              
                 inner join pin on pin.nopendaftaran=pop.nopendaftaran
                 inner join or_account on or_account.nopendaftaran=pop.nopendaftaran
                 inner join tbl_pleno2 on tbl_pleno2.noPendaftaran=pop.nopendaftaran
                 left join po_mayor as ps on tbl_pleno2.psPilihan = ps.kode
                where pin.verifikasi = 1 and tbl_pleno2.idHasilPleno <> 3 and pop.nopendaftaran = '$nopen'
                $filter ;";
        $command = $db->createCommand($sql);
        $data = $command->queryAll();
        foreach ($data as $i => $d)
            $data[$i]['nama'] = \app\models\ModelData::upfistarray($d['nama']);
        if (empty($data))
            return (object)['nama' => ''];
        return (object)$data[0];
    }

    public static function getAntrian($filter = '')
    {
        $db = \Yii::$app->db;
        $sql = "select distinct
                    absen.*,
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
                    
                 from tbl_absensi as absen
                 inner join po_pendaftaran as pop on pop.nopendaftaran=absen.identity
                 inner join pin on pin.nopendaftaran=absen.identity
                 inner join or_account on or_account.nopendaftaran=absen.identity
                 left join po_mayor as p1 on pop.mayor1 = p1.kode
                 left join po_mayor as p2 on pop.mayor2 = p2.kode
                where pin.verifikasi =1
                $filter
                order by absen.dateCreate;";
        $command = $db->createCommand($sql);
        $data = $command->queryAll();
        foreach ($data as $i => $d)
            $data[$i]['nama'] = ModelData::upfistarray($d['nama']);
        return $data;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $data = $this->findOne(['identity' => $this->identity, 'even' => $this->even]);
            if (empty($data)) {
                $this->dateCreate = date('Y-m-d H:i:s');
                $this->dateUpdate = date('Y-m-d H:i:s');
            } else {
                $this->dateUpdate = date('Y-m-d H:i:s');
            }
            return true;
        } else {
            return false;
        }
    }
}
