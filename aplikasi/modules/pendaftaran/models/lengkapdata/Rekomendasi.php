<?php
/**
 * Created by Sublime Text.
 * User: doni
 * Date: 2/2/2017
 * Time: 22:22 
 */

namespace app\modules\pendaftaran\models\lengkapdata;

use app\components\Lang;
use app\modelsDB\PemberiRekomendasi;
use Yii;

use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;

class Rekomendasi extends DynamicModel
{

    public $rekomendasi1;

    public $rekomendasi2;

    public $rekomendasi3;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rekomendasi1','rekomendasi2','rekomendasi3'], 'string'],
            ['rekomendasi2', 'compare', 'compareAttribute' => 'rekomendasi1', 'operator' => '!=',
                'message' => "Rekomendasi Kedua tidak boleh sama dengan Rekomendasi Pertama"],
            ['rekomendasi3', 'compare', 'compareAttribute' => 'rekomendasi2', 'operator' => '!=',
                'message' => "Rekomendasi Ketiga tidak boleh sama dengan Rekomendasi Kedua"],
            ['rekomendasi3', 'compare', 'compareAttribute' => 'rekomendasi1', 'operator' => '!=',
                'message' => "Rekomendasi Ketiga tidak boleh sama dengan Rekomendasi Pertama"],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rekomendasi1' => Lang::t( 'Nama dan Gelar Pemberi Rekomendasi 1', 'Name and Title 1'),
            'rekomendasi2' => Lang::t( 'Nama dan Gelar Pemberi Rekomendasi 2', 'Name and Title 2'),
            'rekomendasi3' => Lang::t( 'Nama dan Gelar Pemberi Rekomendasi 3', 'Name and Title 3'),

        ];
    }

    public static function findData($model)
    {
        $data2 = [];
        $data2['rekomendasi1'] = $data2['rekomendasi2'] = $data2['rekomendasi3'] = '';
        $data = PemberiRekomendasi::find()->where(['pendaftaran_noPendaftaran' => $model::$noPendaftaran])->all();
        $i=1; foreach($data as $value){
            $data2['rekomendasi'.$i]= $value->nama;
            $i++;
        }
        return $data2;
    }

    public function save($noPen)
    {
        if (!$this->validate()) {

            return false;
        }


        
        $rekom = PemberiRekomendasi::find()->where(['pendaftaran_noPendaftaran' => $noPen])->all();
        $rekomendasi[1] = $rekomendasi[2] = $rekomendasi[3] = null;
        $i = 1; foreach($rekom as $value){
            $rekomendasi[$i]= PemberiRekomendasi::findOne($value->id);
            $i++;
        }

        $rekomendasi1 = $rekomendasi[1];
        if (empty($rekomendasi[1]) ) {
            $rekomendasi1 = new PemberiRekomendasi();
            $rekomendasi1->pendaftaran_noPendaftaran = $noPen;
        }
       
            $rekomendasi1->nama = $_POST['Rekomendasi']['rekomendasi1'];
       
        $rekomendasi1->save(false);

        $rekomendasi2 = $rekomendasi[2];
        if (empty($rekomendasi2)) {
            $rekomendasi2 = new PemberiRekomendasi();
            $rekomendasi2->pendaftaran_noPendaftaran = $noPen;
        }
        if($this->rekomendasi2 != null){
            $rekomendasi2->nama = $_POST['Rekomendasi']['rekomendasi2'];
        }
        $rekomendasi2->save(false);
        
        $rekomendasi3 = $rekomendasi[3];
        if (empty($rekomendasi3)) {
            $rekomendasi3 = new PemberiRekomendasi();
            $rekomendasi3->pendaftaran_noPendaftaran = $noPen;
        }
        if($this->rekomendasi3 != null){
            $rekomendasi3->nama = $_POST['Rekomendasi']['rekomendasi3'];
        }      
        $rekomendasi3->save(false);
        

        return true;
    }
}