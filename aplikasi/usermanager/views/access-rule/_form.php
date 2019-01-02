<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\usermanager\models\AccessRole */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="access-role-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'roleName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'roleDescription')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ruleSettings')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'dateCreate')->textInput() ?>

    <?= $form->field($model, 'dateUpdate')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
