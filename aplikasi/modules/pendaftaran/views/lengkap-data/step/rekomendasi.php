<?php
/**
 * Created by Sublime Text.
 * User: doni
 * Date: 2/2/2017
 * Time: 22:22 
 */

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm; */
/* @var $rekomendasi \app\modules\pendaftaran\models\lengkapdata\Rekomendasi */


?>

<div class="row panel">
    <div class="col-lg-10 col-md-6">

        <?= $form->field($rekomendasi, 'rekomendasi1')->input('text', ['value'=>@$dataRekom['rekomendasi1']]) ?>
        <?= $form->field($rekomendasi, 'rekomendasi2')->input('text', ['value'=>@$dataRekom['rekomendasi2']]) ?>
        <?= $form->field($rekomendasi, 'rekomendasi3')->input('text', ['value'=>@$dataRekom['rekomendasi3']]) ?> 
    
    </div>
</div>

