<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\UserFdp */

$this->title = Yii::t('app', 'Create User Fdp');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Fdps'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-fdp-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
