<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \app\usermanager\models\User */
$name = $user->orang == null ? '' : $user->orang->nama;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->passwordResetToken]);
?>
<div class="password-reset">
    <p>
        Name : <?= $name ?><br>
        Username : <?= Html::encode($user->username) ?><br>
    </p>

    <p>Follow the link below to reset your password. This link will expire in 1 hour.:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
