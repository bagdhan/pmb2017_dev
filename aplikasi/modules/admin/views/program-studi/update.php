<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProgramStudi */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Program Studi',
]) . $model->inisial;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Program Studi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="program-studi-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
