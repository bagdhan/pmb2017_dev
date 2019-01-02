<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\GenNrp */

$this->title = Yii::t('app', 'Create Gen Nrp');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Gen Nrps'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gen-nrp-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
