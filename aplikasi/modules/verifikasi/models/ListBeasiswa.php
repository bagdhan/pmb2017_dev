<?php
/**
 * Created by PhpStorm.
 * User: Wisard17
 * Date: 8/28/2017
 * Time: 12:17 AM
 */

namespace app\modules\verifikasi\models;


class ListBeasiswa extends \app\modelsDB\ListBeasiswa
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['pemberiBeasiswa'], 'required'],
        ]);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->dateCreate = $this->isNewRecord ? date('Y-m-d H:i:s') : $this->dateCreate;
            $this->dateUpdate = date('Y-m-d H:i:s');

            return true;
        }
        return false;
    }
}