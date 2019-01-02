<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 2/21/2017
 * Time: 12:41 AM
 */

namespace app\modules\admin\models;

use app\models\PaketPendaftaran;
use app\modelsDB\ManajemenJalurMasuk as MjmProdi;
use app\models\ProgramStudi;
use yii\web\NotFoundHttpException;

/**
 * Class ManajemenJalurMasuk
 *
 * @property PaketPendaftaran paket
 * @property int profesional
 * @property int khusus
 * @property int reguler
 * @property int paketid
 * @property MjmProdi[] mjProdi
 *
 * @package app\modules\admin\models
 *
 *
 */
class ManajemenJalurMasuk extends ProgramStudi
{
    /** @var PaketPendaftaran */
    public $paket;

    /**
     * ManajemenJalurMasuk constructor.
     * @param PaketPendaftaran $paket
     * @param array $config
     */
    public function __construct(PaketPendaftaran $paket = null, array $config = [])
    {
        $this->paket = $paket;
        parent::__construct($config);
    }

    public function getPaketid()
    {
        return $this->paket->id;
    }

    public function getReguler()
    {
        return $this->getStatusManajemen('jalur', 1);
    }

    public function getKhusus()
    {
        return $this->getStatusManajemen('jalur', 2);
    }

    public function getProfesional()
    {
        return $this->getStatusManajemen('jalur', 3);
    }

    protected function getStatusManajemen($sw, $id)
    {
        $filter = ['programStudi_id' => $this->id];
        switch ($sw) {
            case 'jalur':
                $filter['jalurMasuk_id'] = $id;
                break;
            case 'program':
                $filter['program_id'] = $id;
                break;
            default :
                throw new NotFoundHttpException('No Page');
        }
        $manajemen = MjmProdi::find()->where($filter)->one();
        return $this;
    }

    public function getJalur()
    {

    }

    public function getProgram()
    {

    }

    public function getMjProdi()
    {
        return $this->manajemenJalurMasuks;
    }

}