<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\usermanager\models\AccessRole */

$this->title = 'Create Access Role';
$this->params['breadcrumbs'][] = ['label' => 'Access Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-role-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
