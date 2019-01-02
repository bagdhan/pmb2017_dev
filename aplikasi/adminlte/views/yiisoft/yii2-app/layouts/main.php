<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;


\app\adminlte\assets\AdminLTEAsset::register($this);
\app\adminlte\assets\OptionAsset::register($this);
\app\adminlte\assets\LastAsset::register($this);


if (Yii::$app->controller->action->id === 'login') {
    /**
     * Do not use this code in your template. Remove it.
     * Instead, use the code  $this->layout = '//login'; in your controller.
     */
    echo $this->render('login', ['content' => $content]);
} else {

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@app/adminlte/dist/');

    $hideslide = Yii::$app->user->isGuest? "layout-top-nav" : "sidebar-mini";
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue-light <?= $hideslide?> ">
    <?php $this->beginBody() ?>

    <div class="wrapper">


        <?php echo $this->render('header', ['directoryAsset'=>$directoryAsset]);?>

        <?php
            if (!Yii::$app->user->isGuest)
                echo $this->render('left', ['directoryAsset'=>$directoryAsset]);?>

        <?php echo $this->render('content', ['content' => $content]);?>

    </div><!-- ./wrapper -->

    <?php $this->endBody() ?>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-92538063-1', 'auto');
        ga('send', 'pageview');

    </script>
    </body>
    </html>
    <?php $this->endPage();
} ?>

