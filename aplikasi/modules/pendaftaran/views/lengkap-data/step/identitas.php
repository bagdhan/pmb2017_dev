<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 1/26/2017
 * Time: 11:26 AM
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $identitas \app\modules\pendaftaran\models\lengkapdata\DynamicKontakIdentitas*/
/* @var $form yii\widgets\ActiveForm; */

?>

<div class="row panel">
    <div class="col-lg-12 col-md-12">

        <?php

        foreach ($identitas->attributes as $attribute => $value) {

            echo $form->field($identitas, $attribute)->textInput(['disabled' => 'disabled']);

        }
        ?>

    </div>
</div>