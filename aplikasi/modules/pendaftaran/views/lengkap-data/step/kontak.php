<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/26/2017
 * Time: 11:26 AM
 */


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $kontak \app\modules\pendaftaran\models\Kontak[] */
/* @var $form yii\widgets\ActiveForm; */

?>

<div class="row panel">
    <div class="col-lg-12 col-md-12">

        <?php

        foreach ($kontak as $item) {
            $dis = $item->jenisKontak->nama == 'email' ? ['class' => 'form-control', 'disabled' => 'disabled'] : ['class' => 'form-control'];
            $input = Html::activeInput('text', $item, 'kontak', $dis);
            $label = Html::tag(
                    'label',
                    $item->jenisKontak->nama,
                    [
                        'class' => 'col-md-4 control-label',
                        'for' => Html::getInputId($item, 'kontak'),
                    ]);

            echo $label . Html::tag('div', $input, ['class' => 'col-md-8 form-group']);

        }
        ?>

    </div>
</div>
