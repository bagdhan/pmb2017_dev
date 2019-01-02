<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modelsDB\AccessRole;
use app\modelsDB\Fakultas;
use app\modelsDB\Departemen;
use app\modelsDB\ProgramStudi;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\UserFdp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-fdp-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'passwordHash')->passwordInput(['value'=>'']) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'accessRole_id')->dropDownList(
            ArrayHelper::map(AccessRole::find()->where('id in (4,5,6)')->all(),'id','roleName')) ?>

    <?= $form->field($model, 'fakultas')->dropDownList(
            ArrayHelper::map(Fakultas::find()->all(),'id','nama'),[
            'class' => 'select2 form-control', 'prompt' => 'Pilih',
            'onchange'=>'
                        $.get( "'.Yii::$app->urlManager->createUrl(['/api/lists-fakultas','jenis'=>'dep','fakid'=>'']).
                '"+$(this).val(), function( data ) {
                                      $( "select#'.Html::getInputId($model, 'departemen').'" ).select2("val", "");
                                      $( "select#'.Html::getInputId($model, 'departemen').'" ).html( data );
                                      $( "select#'.Html::getInputId($model, 'prodi').'" ).select2("val", "");
                                    });'
        ]) ?>

    <?= $form->field($model, 'departemen')->dropDownList([],[
            'class' => 'select2 form-control', 'prompt' => 'Pilih',
            'onchange'=>'
                        $.get( "'.Yii::$app->urlManager->createUrl(['/api/lists-fakultas','jenis'=>'prodi','depid'=>'']).
                '"+$(this).val(), function( data ) {
                                      $( "select#'.Html::getInputId($model, 'prodi').'" ).html( data );
                                    });'
        ]) ?>

    <?= $form->field($model, 'prodi')->dropDownList([],[
            'class' => 'select2 form-control', 'prompt' => 'Pilih',
        ]) ?>

    <?= $model->listAssign?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
