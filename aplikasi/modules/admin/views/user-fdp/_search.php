<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\SUserFdp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-fdp-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'passwordHash') ?>

    <?= $form->field($model, 'authKey') ?>

    <?= $form->field($model, 'accessToken') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'passwordResetToken') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'dateCreate') ?>

    <?php // echo $form->field($model, 'dateUpdate') ?>

    <?php // echo $form->field($model, 'accessRole_id') ?>

    <?php // echo $form->field($model, 'orang_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
