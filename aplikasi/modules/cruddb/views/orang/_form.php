<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modelsDB\Orang */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orang-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'KTP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tempatLahir')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggalLahir')->textInput() ?>

    <?= $form->field($model, 'jenisKelamin')->textInput() ?>

    <?= $form->field($model, 'statusPerkawinan_id')->textInput() ?>

    <?= $form->field($model, 'NPWP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'waktuBuat')->textInput() ?>

    <?= $form->field($model, 'waktuUbah')->textInput() ?>

    <?= $form->field($model, 'negara_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
