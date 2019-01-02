<?php
/**
 * Created by SubmileText.
 * User: doni17
 * Date: 5/5/2017
 * Time: 2:45 PM
 */

namespace app\modules\admin\models;

use app\models\NrpGenerator;
use app\models\Pendaftaran;

class GenNrp extends \app\modelsDB\GenNrp
{
    public function upfistarray($input){
        $pca = explode(' ', $input);
        $g='';
        foreach ($pca as $p)
            $g[]= ucfirst(strtolower($p));
        return join(' ',$g);
    } 

    public function getName()
    {
        $name = Pendaftaran::findOne($this->noPendaftaran);
        return $this->upfistarray($name->orang->nama);
    }

	public function getNrp()
    {
        $nrp = new NrpGenerator();
        return $nrp->getNrp($this->noPendaftaran);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $data = $this->findOne($this->id);
            if(empty($data)){
                $this->dateCreate=date('Y-m-d H:i:s');
                $this->dateUpdate=date('Y-m-d H:i:s');
            }
            else{
                $this->dateUpdate=date('Y-m-d H:i:s');
            }
            return true;
        } else {
            return false;
        }
    }

}