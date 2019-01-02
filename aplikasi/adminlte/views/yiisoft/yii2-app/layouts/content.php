<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\widgets\Breadcrumbs;
use app\adminlte\widgets\Alert;

$hide = Yii::$app->user->isGuest ? "container" : "";
?>
<div class="content-wrapper">
    <div class="<?= $hide?>">
    <section class="content-header">
        <?php if (isset($this->blocks['content-header'])) { ?>
            <h1><?= $this->blocks['content-header'] ?></h1>
        <?php } else { ?>
            <h1>
                <?php
                if ($this->title !== null) {
                    echo \yii\helpers\Html::encode($this->title);
                } else {
                    echo \yii\helpers\Inflector::camel2words(
                        \yii\helpers\Inflector::id2camel($this->context->module->id)
                    );
                    echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                } ?>
            </h1>
        <?php } ?>

        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
    </section>


    <section class="content">
        <?= Alert::widget() ?>
        <?php
            if(isset($this->params['sidebar']) && $this->params['sidebar'] == 0)
                echo $content;
            else {
        ?>
        <div class="row">
            <div class="col-lg-8">
                <?= $content ?>
            </div>
            <div class="col-lg-4">
                <?= $this->render('pageside') ?>
            </div>
        </div>
        <?php }?>
    </section>
    </div>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.1.0
    </div>
    <strong>Copyright &copy <?= date('Y') ?> Sekolah Pascasarjana IPB.</strong> All rights reserved.
</footer>


<div class='control-sidebar-bg'></div>