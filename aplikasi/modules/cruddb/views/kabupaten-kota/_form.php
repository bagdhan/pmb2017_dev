<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modelsDB\KabupatenKota */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kabupaten-kota-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'namaID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'namaEN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'propinsi_kode')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
