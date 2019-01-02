<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/23/2017
 * Time: 11:03 AM
 */

namespace app\modules\pendaftaran\models\lengkapdata;


use yii\base\DynamicModel;


class Pendidikan extends DynamicModel
{

    public static $strata;

    public static $S1;

    public static $S2;

    public function __construct($strata, array $config = [])
    {
        die;
        self::$strata = $strata;
        self::$S1 = new S1();
        self::$S2 = new S2();
        parent::__construct($config);
    }

    public static function find($strata, $idOrang)
    {
        $s1 = new S1();

        print_r($s1);
        die;
        self::$strata = $strata;
        self::$S1 = S1::find()->where(['strata'=>1, 'orang_id' => $idOrang]);
        self::$S2 = S2::find()->where(['strata'=>2, 'orang_id' => $idOrang]);

        if (empty(self::$S1))
            self::$S1 = new S1();

        if (empty(self::$S2))
            self::$S2 = new S2();
    }

    public function save()
    {
        return false;
    }
}