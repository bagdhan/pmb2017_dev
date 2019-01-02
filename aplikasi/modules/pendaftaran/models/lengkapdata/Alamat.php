<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/23/2017
 * Time: 2:05 PM
 *
 *
 * @property string $kec;
 * @property string $kab;
 * @property string $prov;
 */

namespace app\modules\pendaftaran\models\lengkapdata;

use app\modelsDB\Propinsi;
use Yii;
use app\modelsDB\DesaKelurahan;
use yii\helpers\ArrayHelper;

class Alamat extends \app\modelsDB\Alamat
{
    public $orang_id;

    public $kec;
    public $kab;
    public $prov;

    public $st = 1;

    public function init()
    {
        if (!$this->isNewRecord) {
            if ($this->desaKelurahan_kode != '') {
                $this->setKab();
                $this->setKec();
                $this->setProv();
            }
        }
        return parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jalan', 'kec', 'kab', 'prov', 'desaKelurahan_kode', 'kodePos'], 'required'],
            [['rt', 'rw', 'desaKelurahan_kode'], 'string', 'max' => 10],
            [['kodePos'], 'string', 'max' => 45],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kec' => Yii::t('pmb', 'Districts'),
            'kab' => Yii::t('pmb', 'Regency / City'),
            'prov' => Yii::t('pmb', 'Province'),
            'desaKelurahan_kode' => Yii::t('pmb', 'Village'),

            'jalan' => Yii::t('pmb', 'Address'),
            'kodePos' => Yii::t('pmb', 'Postal code'),


        ];
    }

    /**
     * set Kecamatan
     */
    public function setKec()
    {
        $this->kec = $this->desaKelurahanKode->kecamatan_kode;
    }

    /**
     * set Kabupaten/Kota
     */
    public function setKab()
    {
        $this->kab = $this->desaKelurahanKode->kecamatanKode->kabupatenKota_kode;
    }


    /**
     * set Propinsi
     */
    public function setProv()
    {
        $this->prov = $this->desaKelurahanKode->kecamatanKode->kabupatenKotaKode->propinsi_kode;
    }

    /**
     * @inheritdoc
     */
    public function setOrangId()
    {
        $this->orang_id = $this->orangHasAlamats[1]->orang_id;
    }

    /**
     * @return array
     */
    public static function listProvinsi()
    {
        return ArrayHelper::map(Propinsi::find()->all(), 'kode', 'namaID');
    }
}