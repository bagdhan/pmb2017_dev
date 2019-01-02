<?php

namespace app\modules\pendaftaran\controllers;

use app\components\Controller;
use Yii;
use app\modules\pendaftaran\models\FormVerifikasiPIN;
use app\modules\pendaftaran\models\Pendaftaran;
use app\usermanager\models\AccessRole;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotAcceptableHttpException;

class VerifikasiController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => [ 'index', 'view', 'update', 'create', 'delete', 'pin'],
                'rules' => [
                    // allow authenticated users
                    [
                        'actions' => ['pin'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => [ 'index', 'view', 'update', 'create', 'delete',],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return AccessRole::getAccess($action, $rule);
                        },
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPin()
    {
        // pemblokiran proses verifikasi pin sesuai jadwal paket pendaftaran BEGIN
        $pendaftaran = Pendaftaran::find()->where(['orang_id' => Yii::$app->user->identity->orang_id])->one();
        $paketPendaftar = $pendaftaran->paketPendaftaran;
        if (strtotime($paketPendaftar->dateEnd) < time())
                throw new NotAcceptableHttpException("Pendaftaran telah ditutup,<br> untuk lebih jelasnya  silahkan hubungi 0251 8628448. ");
        // END

        $formVerifikasi = new FormVerifikasiPIN();
        if ($formVerifikasi->load(Yii::$app->request->post())) {
            if ($formVerifikasi->verifikasi()) {
                Yii::$app->session->setFlash('success', "Verifikasi PIN Berhasil.");
                return $this->redirect(['/pendaftaran/lengkap-data?step=1']);
            }
        }

        return $this->render('pin', ['model' => $formVerifikasi]);
    }

}
