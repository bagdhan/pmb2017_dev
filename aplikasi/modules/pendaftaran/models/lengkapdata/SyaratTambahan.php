<?php
/**
 * Created by Sublime Text.
 * User: doni
 * Date: 5/2/2017
 * Time: 9:00 
 */

namespace app\modules\pendaftaran\models\lengkapdata;


use app\components\Lang;
use app\modelsDB\JenisSyaratTambahan;
use Yii;

use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;

class SyaratTambahan extends \app\models\SyaratTambahan
{

    public $tpa;
    public $toefl;

    public $date_tpa;
    public $date_toefl;

    public $org_tpa;
    public $org_toefl;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tpa','toefl'], 'number'],
            [['date_tpa','date_toefl'], 'safe'],
            [['org_tpa','org_toefl'], 'string'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tpa' => Yii::t('app', 'TPA'),
            'toefl' => Yii::t('app', 'TOEFL'),
            'org_tpa' => Lang::t('Penyelenggara test', 'Organizer'),
            'org_toefl' => Lang::t('Penyelenggara test', 'Organizer'),
            'date_tpa' => Yii::t('pmb', 'Date'),
            'date_toefl' => Lang::t('Tanggal', 'Date Taken'),

        ];
    }

    public static function findData($model)
    {
        $data2 = [];
        $data2['tpa']['score'] = $data2['toefl']['score'] = $data2['tpa']['org'] = $data2['toefl']['org'] = $data2['tpa']['date'] = $data2['toefl']['date'] = '';
        $data = self::find()->where(['pendaftaran_noPendaftaran' => $model::$noPendaftaran])->all();
        foreach($data as $value){
            $syarat = strtolower($value->jenisSyaratTambahan->title);
            $data2[$syarat]['score']= $value->score;
            $data2[$syarat]['org']= $value->organizer;
            $data2[$syarat]['date']= $value->dateExercise == null ? null : date('d-m-Y', strtotime($value->dateExercise));;
        }
        return $data2;
    }

    public function saveSyarat($noPen)
    {
        if (!$this->validate()) {

            return false;
        }


        $syarattambahan['toefl'] = $syarattambahan['tpa'] =  null;
        $syarat = self::find()->where(['pendaftaran_noPendaftaran' => $noPen])->all();

        foreach($syarat as $value){
            $syarattitle = strtolower($value->jenisSyaratTambahan->title);
            $syarattambahan[$syarattitle]= self::findOne($value->id);
        }

        $jenisSyarat = JenisSyaratTambahan::find()->all();
        foreach($jenisSyarat as $value){
            $syarattitle = strtolower($value->title);
            $syarat = $syarattambahan[$syarattitle];
            if (empty($syarat) ) {
                $syarat = new self();
                $syarat->pendaftaran_noPendaftaran = $noPen;
                $syarat->jenisSyaratTambahan_id = $value->id;
            }
                
                $syarat->score = isset($_POST['SyaratTambahan'][$syarattitle]) ? $_POST['SyaratTambahan'][$syarattitle] : 0;
                $syarat->dateExercise = isset($_POST['SyaratTambahan']['date_'.$syarattitle]) ? $_POST['SyaratTambahan']['date_'.$syarattitle] : 0;
                $syarat->organizer = isset($_POST['SyaratTambahan']['org_'.$syarattitle]) ? $_POST['SyaratTambahan']['org_'.$syarattitle] : 0;
           
                $syarat->save(false);
        }
        

        return true;
    }

}