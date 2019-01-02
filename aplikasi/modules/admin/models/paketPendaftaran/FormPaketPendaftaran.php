<?php
/**
 * Created by
 * User: Wisard17
 * Date: 25/02/2018
 * Time: 10.38 PM
 */

namespace app\modules\admin\models\paketPendaftaran;


use app\modelsDB\JalurMasuk;

/**
 * Class FormPaketPendaftaran
 * @package app\modules\admin\models\paketPendaftaran
 */
class FormPaketPendaftaran extends PaketPendaftaran
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['deskripsi'], 'string'],
            [['active', 'jalurMasuk_id'], 'integer'],
            [['dateStart', 'dateEnd', 'title', 'jalurMasuk_id'], 'required'],
            [['uniqueUrl'], 'string', 'max' => 100],
            [['title'], 'string', 'max' => 250],
            [['tahun'], 'string', 'max' => 5],
            [['uniqueUrl'], 'unique'],
            [['jalurMasuk_id'], 'exist', 'skipOnError' => true,
                'targetClass' => JalurMasuk::className(), 'targetAttribute' => ['jalurMasuk_id' => 'id']],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {
                $this->tahun = date('Y', strtotime($this->dateStart));
            }

            return true;
        }
        return false;
    }
}