<?php

namespace app\controllers;

use Yii;
use app\modelsDB\PaketPendaftaran;
use app\models\Post;
use app\modules\admin\models\Email;
use app\modules\pendaftaran\models\Pendaftaran;
use yii\web\Controller;

class InfoController extends \app\components\Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        print_r(strtotime('2018-02-05') . ' '. strtotime('2018-02-06') . ' ' . time() . '<br>');
        print_r((strtotime('2018-02-05') - strtotime('2018-02-06')) . ' ' . (time() - strtotime('2018-2-5')) . ' ' . (60 * 60 * 24 *2));

        die;
        return $this->render('index');
    }

    /**
     * @return string
     */
    public function actionTahapanPmb()
    {
        $post = Post::find()->where(['page'=>Yii::$app->controller->action->id])->one();
        return $this->render('tahapan',['post'=>$post]);
    }

    /**
     * @return string
     */
    public function actionTahapank()
    {
        $post = Post::find()->where(['page'=>Yii::$app->controller->action->id])->one();
        return $this->render('tahapank',['post'=>$post]);
    }
	
	public function actionTahapanresearch()
    {
        $post = Post::find()->where(['page'=>Yii::$app->controller->action->id])->one();
        return $this->render('tahapanresearch',['post'=>$post]);
    }
	

    /**
     * @return string
     */
    public function actionDoubleDegree()
    {
        $post = Post::find()->where(['page'=>Yii::$app->controller->action->id])->one();
        return $this->render('ddegree',['post'=>$post]);
    }

    /**
     * @return string
     */
    public function actionCreditEarning()
    {
        $post = Post::find()->where(['page'=>Yii::$app->controller->action->id])->one();
        return $this->render('cearning',['post'=>$post]);
    }

    /**
     * @return string
     */
    public function actionJointDegree()
    {
        $post = Post::find()->where(['page'=>Yii::$app->controller->action->id])->one();
        return $this->render('jdegree',['post'=>$post]);
    }

    /**
     * @return string
     */
    public function actionJadwalPmb()
    {
        $model = PaketPendaftaran::find()->where(['active'=>1,'tahun'=>date('Y')])->all();
        $post = Post::find()->where(['page'=>Yii::$app->controller->action->id])->one();
        return $this->render('jadwalpmb',['model'=>$model,'post'=>$post]);
    }

    /**
     * @return string
     */
    public function actionImageTahapan()
    {
        return $this->render('imgtahapan');
    }

/**
     * @return string
     */
    public function actionKontakkami()
    {
        return $this->render('kontak');
    }

    public function actionListProdi()
    {
        return $this->render('listprodi');
    }


    public function actionTestemail($nopen)
    {
        $pnd = Pendaftaran::findOne(['noPendaftaran' => $nopen]);
        if (!empty($pnd))
            Email::sendEmailLengkap($pnd);
        print_r($pnd);
    }

    public function actionJenis()
    {
        $post = Post::find()->where(['page'=>Yii::$app->controller->action->id])->one();
        return $this->render('jenis',['post'=>$post]);
    }
}
