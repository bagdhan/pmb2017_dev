<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\SearchProgramStudi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="program-studi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'kode') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'nama_en') ?>

    <?= $form->field($model, 'aktif') ?>

    <?php // echo $form->field($model, 'strata') ?>

    <?php // echo $form->field($model, 'inisial') ?>

    <?php // echo $form->field($model, 'kode_nasional') ?>

    <?php // echo $form->field($model, 'sk_pendirian') ?>

    <?php // echo $form->field($model, 'mandat') ?>

    <?php // echo $form->field($model, 'visi_misi') ?>

    <?php // echo $form->field($model, 'departemen_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
