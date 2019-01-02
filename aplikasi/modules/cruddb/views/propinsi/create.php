<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modelsDB\Propinsi */

$this->title = Yii::t('app', 'Create Propinsi');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Propinsis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="propinsi-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
