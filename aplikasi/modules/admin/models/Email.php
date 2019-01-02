<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 2/14/2017
 * Time: 3:50 PM
 */

namespace app\modules\admin\models;

use app\modules\pendaftaran\models\Kontak;
use app\modules\pendaftaran\models\Pendaftaran;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class Email
 * @package app\modules\admin\models
 */
class Email extends Model
{
    /**
     * @param $pendaftar Pendaftaran
     * @return bool
     */
    public static function sendEmailLengkap($pendaftar)
    {
        $email = ArrayHelper::getColumn(Kontak::find()
            ->where(['orang_id' => $pendaftar->orang_id, 'jenisKontak_id' => 1])->asArray()->all(),'kontak');
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'lengkap-html', 'text' => 'lengkap-text'],
                ['model' => $pendaftar, ]
            )
            ->setFrom([Yii::$app->params['norepEmail'] => Yii::$app->name . ' - IPB '])
            ->setTo($email)
            ->setSubject('Verifikasi data lengkap ' . Yii::$app->name)
            ->send();
    }
}