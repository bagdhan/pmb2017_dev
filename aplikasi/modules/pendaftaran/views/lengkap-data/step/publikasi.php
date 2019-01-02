<?php
/**
 * Created by Sublime Text.
 * User: doni
 * Date: 2/2/2017
 * Time: 22:22 
 */

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm; */
/* @var $publikasi \app\modules\pendaftaran\models\lengkapdata\Publikasi */


?>

<div class="row panel">
    <div class="col-lg-10 col-md-6">

        <?= $form->field($publikasi,'jurnalInternasional')->checkBox() ?>
        <?= $form->field($publikasi,'jurnalNasionalAkreditasi')->checkBox() ?>
        <?= $form->field($publikasi,'jurnalNasionalTakAkreditasi')->checkBox() ?>
        <?= $form->field($publikasi,'belum')->checkBox() ?>
    
    </div>
</div>

