<?php
/**
 * Created by
 * User: Wisard17
 * Date: 28/01/2018
 * Time: 05.35 PM
 */

namespace app\modules\admin\models\confirmasiPin;


use Yii;
use app\modules\pendaftaran\models\Pendaftaran;
use yii\helpers\Html;

/**
 * Class Pendaftar
 * @package app\modules\admin\models\confirmasiPin
 *
 * @property string $name
 * @property string $tanggalDaftar
 * @property string $negara
 * @property string $email
 * @property string $action
 */
class Pendaftar extends Pendaftaran
{

    /**
     * @return string
     */
    public function getName()
    {
        return $this->orang->nama;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getTanggalDaftar()
    {
        return Yii::$app->formatter->asDate($this->waktuBuat);
    }

    /**
     * @return string
     */
    public function getNegara()
    {
        return $this->orang->negara == null ? '' : $this->orang->negara->nama;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        if ($this->orang->kontaks == null)
            return '';

        $out = '';
        foreach ($this->orang->kontaks as $kontak) {
            if ($kontak->jenisKontak_id == 1)
                $out .= ($out == '' ? '' : '<br>') . $kontak->kontak;
        }
        return $out;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        $btnApprove = Html::tag('button', '<i class="fa fa-check"></i> Approve', [
            'class' => 'btn btn-xs btn-success dim ',
            'data-action' => 'approve',
            'data-state' => '1',
            'data-id' => $this->noPendaftaran,
        ]);

        $btnDisApprove = Html::tag('button', '<i class="fa fa-times"></i> Dis Approve', [
            'class' => 'btn btn-xs btn-danger dim ',
            'data-action' => 'approve',
            'data-state' => '0',
            'data-id' => $this->noPendaftaran,
        ]);

        $btn = $this->pinVerifikasi != null && $this->pinVerifikasi->status == 1 ? $btnDisApprove : $btnApprove;

        return "$btn";
    }
}