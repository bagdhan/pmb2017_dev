<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/29/2017
 * Time: 12:55 PM
 */

namespace app\modules\pendaftaran\models\lengkapdata;


use app\modelsDB\User;
use app\modules\pendaftaran\models\Identitas;
use app\modules\pendaftaran\models\Kontak;
use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;


/**
 * Class DynamicKontakIdentitas
 * @package app\modules\pendaftaran\models\lengkapdata
 *
 *
 */
class DynamicKontakIdentitas extends DynamicModel
{

    private $__attributes;

    private $__rules;

    private $__labels;

    public $idOrang;

    public static $idOrangStatic;

    /**
     * DynamicKontakIdentitas constructor.
     * @param int $idOrang
     * @param array $attributes
     * @param array $config
     */
    public function __construct($idOrang = 0, array $attributes = [], array $config = [])
    {
        $this->idOrang = $idOrang;
        self::$idOrangStatic = $idOrang;

        $this->__attributes = static::setKontakAttribut()['attribute'];
        $this->__attributes = ArrayHelper::merge($this->__attributes, static::setIdentitasAttribut()['attribute']);;
        $this->__rules = static::setKontakAttribut()['rules'];
        $this->__rules = ArrayHelper::merge($this->__rules, static::setIdentitasAttribut()['rules']);;
        $this->__labels = static::setKontakAttribut()['labels'];
        $this->__labels = ArrayHelper::merge($this->__labels, static::setIdentitasAttribut()['labels']);;

        $attributes = ArrayHelper::merge($attributes, $this->__attributes);

        parent::__construct($attributes, $config);
    }


    /**
     * @return array
     */
    public function rules()
    {
        return $this->__rules;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return $this->__labels;
    }

    /**
     * @return array
     */
    private static function setKontakAttribut()
    {
        $kontak = Kontak::find()->where(['orang_id' => self::$idOrangStatic])->all();
        $output = [];
        $output['attribute'] = [];
        $listV = [
            'No Handphone' => 'Mobile Phone',
            'No Telp. Rumah' => 'Telephone Number',
        ];
        /** @var Kontak $item */
        foreach ($kontak as $item) {
            $jenis = str_replace(' ', '', $item->jenisKontak->nama);
            $jenis = str_replace('-', '', $jenis);
            $jenis = str_replace('_', '', $jenis);
            $name = $jenis . '_' . $item->id . '_' . 'kontak' . '_attribute';
            $attribute = static::syncAttribute($output['attribute'], $name);
            $output['attribute'][$attribute] = $item->kontak;

            if (isset($output[$jenis])) {
                $output[$jenis] += 1;
                $addl = ' ' . $output[$jenis];
            } else {
                $output[$jenis] = 1;
                $addl = '';
            }
            $label = isset($listV[$item->jenisKontak->nama])? $listV[$item->jenisKontak->nama] : $item->jenisKontak->nama;

            $output['labels'][$attribute] = $label . ' ' . $addl;
            if (!(strpos($jenis, 'Rumah') !== false || strpos($jenis, 'rumah') !== false))
                $output['rules'][] = [$attribute, 'required'];
            if (strpos($jenis, 'email') !== false || strpos($jenis, 'Email') !== false)
                $output['rules'][] = [$attribute, 'email'];

            if (strpos($jenis, 'Rumah') !== false || strpos($jenis, 'rumah') !== false)
                $output['rules'][] = [$attribute, 'match', 'pattern' => '/^\+62[1-9]\d{7,}$/i',
                    'message' => 'Format penulisan tidak sesuai, tuliskan nomor kontak telpon dengan format +62xxxxxxxxxxx 
                    (tambahkan kode-area kota Anda tanpa angka NOL). Contoh: +622518628448 
                    (+62 = kode INDONESIA; 251 = kab/kota BOGOR; 8628448 = nomor telpon'];

            if (strpos($jenis, 'HP') !== false || strpos($jenis, 'Hp') !== false)
                $output['rules'][] = [$attribute, 'match', 'pattern' => '/^\+62[8]\d{5,}$/i',
                    'message' => 'Format penulisan tidak sesuai, tuliskan nomor kontak telpon mobile Anda dengan 
                    format +628xxxxxxxxxxx (TANPA angka NOL pada nomor mobile-nya. Contoh: +6281212344321 
                    (+62 = kode INDONESIA; 81212344321 = nomor mobile).'];


        }

        return $output;
    }

    /**
     * @return array
     */
    private static function setIdentitasAttribut()
    {
        $identitas = Identitas::find()->where(['orang_id' => self::$idOrangStatic])->all();
        $output = [];
        $output['attribute'] = [];
        $listV = [
            'KTP' => 'KTP',
            'Paspor' => 'Paspor',
        ];
        /** @var Identitas $item */
        foreach ($identitas as $item) {
            $jenis = str_replace(' ', '', $item->jenisIdentitas->nama);
            $jenis = str_replace('-', '', $jenis);
            $jenis = str_replace('_', '', $jenis);
            $name = $jenis . '_' . $item->id . '_' . 'identitas' . '_attribute';
            $attribute = static::syncAttribute($output['attribute'], $name);
            $output['attribute'][$attribute] = $item->identitas;

            if (isset($output[$jenis])) {
                $output[$jenis] += 1;
                $addl = ' ' . $output[$jenis];
            } else {
                $output[$jenis] = 1;
                $addl = '';
            }
            $label = isset($listV[$item->jenisIdentitas->nama])? $listV[$item->jenisIdentitas->nama] : $item->jenisIdentitas->nama;

            $output['labels'][$attribute] = $label . ' ' . $addl;

            $output['rules'][] = [$attribute, 'required'];

        }

        return $output;
    }

    /**
     * @param array $attributeArray
     * @param $attribute
     * @return string
     */
    public static function syncAttribute(array $attributeArray, $attribute)
    {
        if (isset($attributeArray[$attribute])) {
            if ((int)substr($attribute, -1) > 0)
                $attribute = $attribute . ((int)substr($attribute, -1) + 1);
            else
                $attribute = $attribute . (1);
        }

        return $attribute;
    }

    /**
     * @return bool
     */
    private function setAttributValue()
    {
        foreach ($this->__attributes as $attribute => $value) {
            $part = explode('_', $attribute);
            $id = $part[1];
            $jenis = $part[2];
            if ($jenis == 'identitas') {
                $model = Identitas::findOne($id);
            } else if ($jenis == 'kontak') {
                $model = Kontak::findOne($id);
            }

            if (!empty($model)) {
                $this->__attributes[$attribute] = $model->$jenis;
            }
        }
        return true;
    }

    public function save()
    {
//        print_r($this->attributes);die;
        foreach ($this->attributes as $attribute => $value) {
            $part = explode('_', $attribute);
            $id = $part[1];
            $jenis = $part[2];
            if ($jenis == 'identitas') {
                $model = Identitas::findOne($id);
                $model->identitas = $value;
            } else if ($jenis == 'kontak') {
                $model = Kontak::findOne($id);
                $model->kontak = $value;
                if ($model->jenisKontak_id == 1) {
                    $user = User::find()->where(['orang_id' => self::$idOrangStatic])->one();
                    $user->email = $value;
                    $user->save(false);
                }
            }

            if (!empty($model) && !$model->save(false)) {
                //return false;
            }
        }
        return true;
    }

}