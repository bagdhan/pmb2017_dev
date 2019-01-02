<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


\app\adminlte\assets\plugin\CKEditorAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modelsDB\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subtitle_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['class'=>'ckeditor','rows' => 6]) ?>

    <?= $form->field($model, 'content_en')->textarea(['class'=>'ckeditor','rows' => 6]) ?>

    <?= $form->field($model, 'page')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
