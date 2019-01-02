<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\LoginForm */

\app\adminlte\assets\plugin\iCheckAsset::register($this);
$this->title = 'Sign In';

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@app/adminlte/dist/');

$activUrl = \app\models\PaketPendaftaran::findActive('Reguler');
?>

<div class="login-box">
    <div class="login-logo">
        <a href="<?= Yii::$app->homeUrl ?>">
        <img height="100" src="<?= $directoryAsset ?>/img/logo.png">
        <br>
        <b>Pendaftaran Online</b></a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg"></p>
        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>
        <?= $form
            ->field($model, 'username', [
                'options' => ['class' => 'form-group has-feedback'],
                'template' => "
                        {input}
                        <span class=\"glyphicon glyphicon-user form-control-feedback\"></span>
                        {error}"
            ])
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

        <!--<?= $form
            ->field($model, 'password', [
                'options' => ['class' => 'form-group has-feedback'],
                'template' => "
                        {input}
                        <span class=\"glyphicon glyphicon-lock form-control-feedback\"></span>
                        {error}"
            ])
			
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>!-->
<?= $form->field($model, 'password')->textInput(['class' => 'form-control dateinput']) ?>
        <div class="row">
            <!-- <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>!-->
            <div class="col-xs-4">
                <?= Html::submitButton('Sign in <i class="icon-arrow-right14 position-right"></i> ',
                    ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

        <!--<?= Html::a('I forgot my password', ['/site/request-password-reset']) ?><br>
        <?= Html::a('Register a new membership', ['/register', 'jenismasuk' => $activUrl], ['class'=>'text-center']) ?>!-->

    </div><!-- /.login-box-body -->
</div>
