<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pleno\models\Sidang */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sidang-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tanggalSidang')->textInput() ?>

    <?= $form->field($model, 'deskripsi')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'kunci')->textInput() ?>

    <?= $form->field($model, 'jenisSidang_id')->textInput() ?>

    <?= $form->field($model, 'paketPendaftaran_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
