<?php
/**
 * Created by
 * User: Wisard17
 * Date: 25/02/2018
 * Time: 10.39 PM
 */


use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model \app\modules\admin\models\paketPendaftaran\FormPaketPendaftaran */


?>

        <div class="user-fdp-form">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'deskripsi')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'dateStart')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'dateEnd')->textInput(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

