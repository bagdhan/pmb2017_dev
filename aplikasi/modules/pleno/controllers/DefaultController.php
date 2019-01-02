<?php

namespace app\modules\pleno\controllers;

use app\modules\pleno\models\Pendaftar;
use yii\web\Controller;

/**
 * Default controller for the `pleno` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $pelamar = Pendaftar::findOne('17000018');
        return $this->render('../temp/detail_person', ['dataPelamar' => $pelamar]);
    }
}
