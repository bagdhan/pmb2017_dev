<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/19/2017
 * Time: 6:52 PM
 */

namespace app\models;


use yii\base\Model;

class CostumDate extends Model
{
    public static function tiemZone()
    {
        date_default_timezone_set("Asia/Jakarta");
    }
}