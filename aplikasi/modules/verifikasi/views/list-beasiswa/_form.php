<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\verifikasi\models\ListBeasiswa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="list-beasiswa-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'namaBeasiswa')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pemberiBeasiswa')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
