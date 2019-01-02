<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 2/16/2017
 * Time: 3:56 PM
 */

namespace app\models;


use app\modelsDB\JalurMasuk;

/**
 * Class PaketPendaftaran
 *
 * @package app\models
 *
 * @property int $jalurMasuk
 */
class PaketPendaftaran extends \app\modelsDB\PaketPendaftaran
{


    /**
     * 1 : Reguler
     * 2 : Khusus
     * 3 : Profesional
     *
     * @return int
     */
    public function getJalurMasuk()
    {
        return empty($this->paketPendaftaranHasManajemenJalurMasuks) ?
            1 : $this->paketPendaftaranHasManajemenJalurMasuks[0]->manajemenJalurMasuk->jalurMasuk_id;
    }

    /**
     * @param $idfakdep
     * @param string $switch
     * @return bool
     */
    public function cekAda($idfakdep, $strata, $switch = "dep")
    {

        foreach ($this->manajemenJalurMasuks as $manajemenJalurMasuk) {
            if ($switch == "dep")
                if ($idfakdep == $manajemenJalurMasuk->programStudi->departemen_id &&
                    $strata == $manajemenJalurMasuk->programStudi->strata &&
                    $manajemenJalurMasuk->aktif == 1
                )
                    return true;
            if ($switch == "fak")
                if ($idfakdep == $manajemenJalurMasuk->programStudi->departemen->fakultas_id &&
                    $strata == $manajemenJalurMasuk->programStudi->strata &&
                    $manajemenJalurMasuk->aktif == 1
                )
                    return true;
        }

        return false;
    }

    public static function activeUrl()
    {
        return self::findOne(2)->uniqueUrl;
    }

    public static function activeUrlKhusus()
    {
        return self::findOne(3)->uniqueUrl;
    }
	
	public static function activeUrlbyResearch()
    {
        return self::findOne(4)->uniqueUrl;
    }

    /**
     * @param $jalurmasuk
     * @param $strict
     * @return mixed|string
     */
    public static function findActive($jalurmasuk, $strict = false)
    {
        $jalur = JalurMasuk::find()->where(['like', 'nama', $jalurmasuk])->one();

        $q = PaketPendaftaran::find()
            ->where(['jalurMasuk_id' => $jalur != null ? $jalur->id : 1, 'active' => 1]);
        if ($strict)
            $q->andWhere('dateStart <= NOW()')
                ->andWhere('dateEnd >= NOW()');
        $paketPendaftar = $q->one();

        return $paketPendaftar == null ? 'not_available' : $paketPendaftar->uniqueUrl;
    }
}