<?php
/**
 * Created by SubmileText.
 * User: doni17
 * Date: 5/5/2017
 * Time: 2:45 PM
 */

namespace app\models;


class Fakultas extends \app\modelsDB\Fakultas
{

	public static function getFakultas($fak=[])
    {
        $allFtr = join("','",$fak);
        $model = Fakultas::find();
        if(sizeof($fak)>0){
            $where = "kode IN ('$allFtr')";
            $data = $model->where($where)->all();
        }else{ 
            $data = $model->all();
        }
        
        return $data;
    }

	/**
     * @return array $data[]
     */
	public static function getTandatangan($fak,$jab)
    {
        $data=[];
        $jabatan = array('', 'dekan', 'wadek_1','wadek_2');
        $model = Fakultas::find()->where(['kode'=>$fak])->one();
        $namajabatan = $jabatan[$jab];
        $nipjabatan = 'nip_'.$namajabatan;

        $data['jabatan'] = $jabatan[$jab];;
        $data['nama'] = $model->$namajabatan;
        $data['nip'] = $model->$nipjabatan;
        
        return $data;
    }

}