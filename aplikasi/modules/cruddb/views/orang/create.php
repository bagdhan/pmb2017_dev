<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modelsDB\Orang */

$this->title = Yii::t('app', 'Create Orang');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orangs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orang-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
