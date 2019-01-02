<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 2/4/2017
 * Time: 12:24 PM
 */

namespace app\modules\pendaftaran\models\lengkapdata;

use app\components\Lang;
use app\modules\pendaftaran\models\Pendaftaran;
use Yii;
use app\modelsDB\JenisPembiayan;
use app\modelsDB\RencanaPembiayaan;
use yii\helpers\ArrayHelper;

/**
 * Class PerencanaanBiaya
 *
 * @package app\modules\pendaftaran\models\lengkapdata
 *
 * @property array listJenis
 */
class PerencanaanBiaya extends RencanaPembiayaan
{

    public function rules()
    {
        return [
            [['jenisPembiayan_id', 'deskripsi'], 'required'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'jenisPembiayan_id' => Lang::t('Jenis Pembiayaan/ Beasiswa', 'Scholarship'),
            'deskripsi' => Lang::t('Sebutkan pembiayaan', 'Mention the source of funding'),
        ];
    }

    /**
     * @return array
     */
    public function getListJenis()
    {
        $out = [];
        $listJenis = [
            'Biaya Sendiri' => 'Personal',
            'Lainnya' => 'Other',
        ];
        foreach (JenisPembiayan::find()->where('id not in (8, 5)')->all() as $idx => $item) {
            $out[$item->id] = Lang::t($item->title, isset($listJenis[$item->title]) ? $listJenis[$item->title] : $item->title );
        }
        $item = JenisPembiayan::findOne(['id' => 8]);
        $out[$item->id] = Lang::t($item->title, isset($listJenis[$item->title]) ? $listJenis[$item->title] : $item->title );
        return $out;
    }

    public function savePost($model)
    {

        // Start herer Ubah sedikit by Doni 
        $id = $_POST['PerencanaanBiaya']['jenisPembiayan_id'];
       if ($id >= 6) {
           $RencanaPembiayaan=  new RencanaPembiayaan();
           $RencanaPembiayaan->jenisPembiayan_id = $id;
           $RencanaPembiayaan->deskripsi = isset($_POST['PerencanaanBiaya']['deskripsi']) ? $_POST['PerencanaanBiaya']['deskripsi'] : '';
           $RencanaPembiayaan->save();
           $id = $RencanaPembiayaan->id;
       }
        // end here

        $pendaftaran = Pendaftaran::findOne(['noPendaftaran' => $model::$noPendaftaran]);
        $pendaftaran->rencanaPembiayaan_id = $id;

        return $pendaftaran->save();
    }
}