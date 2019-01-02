<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProgramStudi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="program-studi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nama_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'aktif')->textInput() ?>

    <?= $form->field($model, 'strata')->textInput() ?>

    <?= $form->field($model, 'inisial')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kode_nasional')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sk_pendirian')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'mandat')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'visi_misi')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'departemen_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
