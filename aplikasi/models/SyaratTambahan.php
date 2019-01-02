<?php
/**
 * Created by Sublime text.
 * User: doni
 * Date: 5/2/2017
 * Time: 12:30
 */

namespace app\models;


class SyaratTambahan extends \app\modelsDB\SyaratTambahan
{
    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {
           
            $this->dateExercise = $this->dateExercise == null ? null : date('Y-m-d H:i:s', strtotime($this->dateExercise));
            return true;
        }
        return false;
    }

}