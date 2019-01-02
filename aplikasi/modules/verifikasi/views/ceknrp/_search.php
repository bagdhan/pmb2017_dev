<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\GenNrpSearch2 */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gen-nrp-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'noPendaftaran') ?>

    <?= $form->field($model, 'kodeProdi') ?>

    <?= $form->field($model, 'tahunMasuk') ?>

    <?= $form->field($model, 'kodeKhusus') ?>

    <?php // echo $form->field($model, 'nourut') ?>

    <?php // echo $form->field($model, 'kode_masuk') ?>

    <?php // echo $form->field($model, 'locknrp') ?>

    <?php // echo $form->field($model, 'date_create') ?>

    <?php // echo $form->field($model, 'date_update') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
