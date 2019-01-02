<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 3/13/2017
 * Time: 3:07 PM
 */

namespace app\modules\pendaftaran\models;

use Yii;
use yii\base\Model;
use app\models\Orang;
use app\usermanager\models\User;
use yii\helpers\ArrayHelper;

/**
 * Class ListResetUser
 * @package app\modules\pendaftaran\models
 *
 *
 */
class ListResetUser extends Model
{

    /**
     * @param Orang $orang
     * @param User $user
     * @param string $listNopen
     * @return bool
     */
    public function sendEmail($orang, $user, $listNopen)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'resetakun-html', 'text' => 'resetakun-text'],
                ['model' => $orang, 'user' => $user, 'listNopen' => $listNopen]
            )
            ->setFrom([Yii::$app->params['norepEmail'] => Yii::$app->name . ' - IPB '])
            ->setTo($user->email)
            ->setSubject('Confirmation User ' . Yii::$app->name)
            ->send();

    }

    /**
     * @param $idOrang
     * @param $email
     * @return User|null
     */
    public function setUser($idOrang, $email)
    {
        $user = User::findOne(['email' => $email]);
        if (empty($user)) {
            $user = new User();
            $user->orang_id = $idOrang;
        } else {
            $user->generatePasswordResetToken();
            return $user->save() ? $user : null;
        }
        $user->username = "username$idOrang";
        $user->email = $email;
        $user->accessRole_id = 7; // rule pendaftar
        $user->setPassword("123456789");
        $user->generateAuthKey();
        $user->generatePasswordResetToken();

        return $user->save() ? $user : null;
    }

    /**
     * @param string $email
     * @return array
     */
    public static function getListNopen($email)
    {
        $listOrg = Kontak::find()->where(['kontak' => $email])->asArray()->all();
        $arr = ArrayHelper::getColumn($listOrg,'orang_id');
        if (empty($arr))
            $arr = [0];
        $dataDaftar = Pendaftaran::find()->where("orang_id IN (" . join(',', $arr) . ")")->all();
        $output = [];
        foreach ($dataDaftar as $item) {
            $output[$item->noPendaftaran] = $item->noPendaftaran;
        }
        return $output;
    }

    public function getSimpleData()
    {
        $db = Yii::$app->db;
        $sql = "SELECT orang.id, orang.nama, orang.KTP, kontak.kontak as email FROM pmbpasca.orang 
            inner join kontak on kontak.orang_id = orang.id
            where 
                orang.id not IN (SELECT orang_id FROM pmbpasca.user where orang_id > 0)
                and kontak.jenisKontak_id = 1";
        $command = $db->createCommand($sql);
        $data = $command->queryAll();

        return $data;
    }

    public function getOneData($id)
    {
        $db = Yii::$app->db;
        $sql = "SELECT orang.id, orang.nama, orang.KTP, kontak.kontak as email FROM pmbpasca.orang 
            inner join kontak on kontak.orang_id = orang.id
            where 
               -- orang.id not IN (SELECT orang_id FROM pmbpasca.user where orang_id > 0)
               orang.id = $id
                and kontak.jenisKontak_id = 1";
        $command = $db->createCommand($sql);
        $data = $command->queryAll();

        return $data;
    }

}