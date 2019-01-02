<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\pleno\models\Sidang */

$this->title = Yii::t('app', 'Create Sidang');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sidangs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sidang-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
