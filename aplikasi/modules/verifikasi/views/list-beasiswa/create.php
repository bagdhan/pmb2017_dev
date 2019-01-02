<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\verifikasi\models\ListBeasiswa */

$this->title = Yii::t('app', 'Create List Beasiswa');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'List Beasiswas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="list-beasiswa-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
