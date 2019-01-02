<?php
/**
 * Created by Sublime Text.
 * User: doni
 * Date: 04/01/2016
 * Time: 1:36 PM
 */

namespace app\modules\pendaftaran\models\lengkapdata;


use app\components\Lang;
use app\modelsDB\KaryaIlmiah;
use Yii;

class Publikasi extends KaryaIlmiah
{
	/**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
    
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->noPendaftaran = Yii::$app->user->identity->orang->pendaftarans[0]->noPendaftaran;
            }

            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'noPendaftaran' => Lang::t('No Pendaftaran', ''),
            'jurnalInternasional' => Lang::t('Jurnal Internasional', 'International Journal'),
            'jurnalNasionalAkreditasi' => Lang::t('Jurnal Nasional Akreditasi', 'Accredited National Journal'),
            'jurnalNasionalTakAkreditasi' => Lang::t('Jurnal Nasional Tak Akreditasi', 'Non-Accreditated National Journal'),
            'belum' => Lang::t('Belum', 'Not Yet'),
        ];
    }

}