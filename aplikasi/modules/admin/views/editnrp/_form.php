<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\GenNrp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gen-nrp-form">


    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'noPendaftaran')->textInput(['maxlength' => true, 'readonly'=>'']) ?>

    <?= $form->field($model, 'kodeProdi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tahunMasuk')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kodeKhusus')->textInput() ?>

    <?= $form->field($model, 'noUrut')->textInput() ?>

    <?= $form->field($model, 'kodeMasuk')->textInput() ?>

    <?= $form->field($model, 'lockNrp')->textInput() ?>

 

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
