<?php

namespace app\modules\admin\controllers;

class DefaultController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionListUserNopen()
    {
        $db = \Yii::$app->db;
        $sql = "select orang.id, orang.nama, user.username, user.email from orang 
                join user on user.orang_id = orang.id
                where orang.id not in (select orang_id from pendaftaran)";
        $command = $db->createCommand($sql);
        $data = $command->queryAll();

        return $this->render('list', ['data'=>$data]);
    }

}
