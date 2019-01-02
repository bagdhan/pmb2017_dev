<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ProgramStudi */

$this->title = Yii::t('app', 'Create Program Studi');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Program Studi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="program-studi-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
