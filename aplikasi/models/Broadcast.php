<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 4/26/2017
 * Time: 12:06 PM
 */

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * @property mixed listEmail
 */
class Broadcast extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;

    public $paket;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [[ 'subject', 'body', 'paket'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],

        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [

        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function send($email)
    {

        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([Yii::$app->params['adminEmail'] => "PMB Pascasarjana IPB"])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();

    }

    public function submit()
    {
        $mssg = '';
        foreach ($this->listEmail as $item) {
            $ket = $this->send($item)? 'berhasil' : "tidak berhasil";
            $mssg .= "$item $ket <br>";

        }

        Yii::$app->session->setFlash('brcFormSubmitted', $mssg);
        return true;
    }

    public function getListEmail()
    {
        $out = [];
//        return [1=>'wisard.kalengkongan@gmail.com'];
        /** @var Pendaftaran[] $pendaftaran */
        $pendaftaran = Pendaftaran::find()->where(['paketPendaftaran_id' => $this->paket])->all();
        $i=1;
        foreach ($pendaftaran as $item) {
            if ($item->pinVerifikasi->status == 1 && $item->setujuSyarat != 1) {
                $out[$i] = $item->orang->users[0]->email;
//                echo $item->orang->users[0]->email . " $i<br>";
                $i++;
            }
        }

        return $out;
    }
}