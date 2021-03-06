<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\cruddb\models\SearchKabupatenKota */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kabupaten-kota-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'kode') ?>

    <?= $form->field($model, 'namaID') ?>

    <?= $form->field($model, 'namaEN') ?>

    <?= $form->field($model, 'propinsi_kode') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
