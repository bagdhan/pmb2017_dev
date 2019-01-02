<?php
/**
 * Created by
 * User: Wisard17
 * Date: 28/01/2018
 * Time: 05.05 PM
 */

namespace app\modules\admin\controllers;

use app\modules\admin\models\confirmasiPin\Pendaftar;
use Yii;
use app\modules\admin\models\confirmasiPin\Search;
use yii\web\Controller;
use yii\web\Response;


/**
 * Class ConfirmasiPinController
 * @package app\modules\admin\controllers
 */
class ConfirmasiPinController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new Search();
        $request = Yii::$app->request->queryParams;

        if (Yii::$app->request->isAjax) {
            return $searchModel->searchDataTable($request);
        }

        return $this->render('index');
    }

    public function actionSetApprove()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $nopen = isset($post['nop']) ? $post['nop'] : '';
            $state = isset($post['state']) ? $post['state'] : '';
            $pendaftar = Pendaftar::findOne(['noPendaftaran' => $nopen]);
            Yii::$app->response->format = Response::FORMAT_JSON;
            $pinVer = $pendaftar->pinVerifikasi;
            if ($pinVer == null)
                return ['status' => 'error'];
            $pinVer->status = $state;
            $pinVer->dateVerifikasi = $state == 0 ? null : date('Y-m-d H:i:s');
            $pinVer->ipVerifikasi = Yii::$app->request->userIP;

            $state2 = $pinVer->save();

            $email = $pendaftar->email;
            $email = explode('<br>', $email);

            if ($state2 && $state != 0)
                $this->sendEmail($pendaftar, $email);

            return [
                'status' => $state2 ? 'success' : 'error',
                'button_action' => $pendaftar->action,
            ];
        }
        return '';
    }

    /**
     * @param Pendaftar $pendaftaran
     * @return bool
     */
    public function sendEmail($pendaftaran, $email)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'pinWna-html', 'text' => 'pinWna-text'],
                ['model' => $pendaftaran, ])
            ->setFrom([Yii::$app->params['norepEmail'] => Yii::$app->name . ' - IPB '])
            ->setTo($email)
            ->setSubject('Verification Account ' . Yii::$app->name)
            ->send();
    }
}