<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 2/1/2017
 * Time: 5:35 PM
 */

namespace app\modules\pendaftaran\models\lengkapdata;

use app\components\Lang;
use Yii;
use app\models\Orang;
use app\modelsDB\Institusi;

class S2 extends S1
{

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'namaUniversitas' => Lang::t('Nama Perguruan Tinggi', 'Name of University'),
            'jalan' => Lang::t('Alamat Universitas', 'Address of University'),
            'orang_id' => Yii::t('pmb', 'Orang ID'),
            'strata' => Lang::t('Strata', 'Degree'),
            'fakultas' => Lang::t('Fakultas', 'Faculty'),
            'programStudi' => Lang::t('Program Studi', 'Study Program'),
            'akreditasi' => Yii::t('pmb', 'Akreditasi'),
            'gelar' => Yii::t('pmb', 'Degree'),
            'jumlahSKS' => Lang::t('Jumlah SKS', 'Credits'),
            'ipk' => Lang::t('IPK', 'GPA'),
            'ipkAsal' => Yii::t('pmb', 'Ipk Asal'),
            'tahunMasuk' => Lang::t('Tahun Masuk', 'Entry Year'),
            'tanggalLulus' => Lang::t('Tanggal Lulus', 'Year of Graduation'),
            'noIjazah' => Lang::t('No Ijazah', 'Diploma number'),
            'judulTA' => Lang::t('Judul Tesis', 'Title of Dissertation'),
            'kec' => Yii::t('pmb', 'Districts'),
            'kab' => Yii::t('pmb', 'Regency / City'),
            'prov' => Yii::t('pmb', 'Province'),
            'des' => Yii::t('pmb', 'Village'),
            'negaraText' => Lang::t('Negara', 'Country'),
            'negara' => Lang::t('Negara', 'Country'),
        ];
    }

}