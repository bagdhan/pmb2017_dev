<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/19/2017
 * Time: 12:07 PM
 */

namespace app\modules\pendaftaran\models;

use Yii;
use app\modules\pendaftaran\models\lengkapdata\PerencanaanBiaya;
use yii\helpers\ArrayHelper;

/**
 * Class Pendaftaran
 * @package app\modules\pendaftaran\models
 *
 * @property PerencanaanBiaya $perencanaanBiaya
 */
class Pendaftaran extends \app\models\Pendaftaran
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerencanaanBiaya()
    {
        return $this->hasOne(PerencanaanBiaya::className(), ['id' => 'rencanaPembiayaan_id']);
    }

    /**
     * @return bool
     *
     */
    public function sendEmail()
    {
        $email = ArrayHelper::getColumn(Kontak::find()
            ->where(['orang_id' => $this->orang_id, 'jenisKontak_id' => 1])->asArray()->all(),'kontak');
        $username = $this->orang_id == null ? '' : ( empty($this->orang->user) ? '' : $this->orang->user->username);
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'akun-html', 'text' => 'akun-text'],
                ['model' => $this, 'username' => $username, 'email' => $email[0],]
            )
            ->setFrom([Yii::$app->params['norepEmail'] => Yii::$app->name . ' - IPB '])
            ->setTo($email)
            ->setSubject('Informasi Akun ' . Yii::$app->name)
            ->send();

    }
}