<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \app\usermanager\models\User */
$name = $user->orang == null ? '' : $user->orang->nama;
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->passwordResetToken]);
?>
Name : <?= $name ?>
Username : <?= Html::encode($user->username) ?>

Follow the link below to reset your password:

<?= $resetLink ?>
