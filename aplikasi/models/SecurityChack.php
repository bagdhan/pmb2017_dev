<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/19/2017
 * Time: 6:14 PM
 */

namespace app\models;


use yii\base\Model;

class SecurityChack extends Model
{
    public static function checkSaveModel($model)
    {
        
        return true;
    }
}