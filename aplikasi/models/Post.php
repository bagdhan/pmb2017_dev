<?php
/**
 * Created by sublime text.
 * User: doni46
 * Date: 1/28/2018
 * Time: 11:00 PM
 */

namespace app\models;


class Post extends \app\modelsDB\Post
{
	public function beforeSave($insert)
    {
        CostumDate::tiemZone();
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->dateCreated = date('Y-m-d H:i:s');
                $this->dateModified = date('Y-m-d H:i:s');
            } else {
                $this->dateModified = date('Y-m-d H:i:s');
            }

            return SecurityChack::checkSaveModel($this);
        }
        return false;
    }

}