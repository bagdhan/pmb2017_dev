<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\VerifikasiAkunForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Form Pembuatan Akun';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div><!-- /.box-header -->
        <div class="box-body">

            <?php $form = ActiveForm::begin([
                'id' => 'form-signup',
                "options" => [
                    "class" => 'form-horizontal'
                ],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-9\">{input}{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-3 control-label'],
                ],
            ]); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'email', ['inputOptions' => ['readonly' => '']]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'password2')->passwordInput() ?>

            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-9">
                    <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
