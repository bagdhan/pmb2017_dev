<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\usermanager\Models\SearchAccessRule */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="access-role-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'roleName') ?>

    <?= $form->field($model, 'roleDescription') ?>

    <?= $form->field($model, 'ruleSettings') ?>

    <?= $form->field($model, 'dateCreate') ?>

    <?php // echo $form->field($model, 'dateUpdate') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
