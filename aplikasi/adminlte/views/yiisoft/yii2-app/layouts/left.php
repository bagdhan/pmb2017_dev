<?php
/**
 *
 * @var $directoryAsset string
 * @var $this \yii\web\View
 * @var $content string
 *
 */
use app\models\Orang;
use yii\web\View;

$username = Yii::$app->user->isGuest ? 'User' : Yii::$app->user->identity->username;

$deskripUser = Yii::$app->user->identity->accessRole_id > 0 ? ' - ' . Yii::$app->user->identity->accessRole->roleName : ' - Guest' ;


$dirfoto = Orang::getPhoto();
$urlto = \yii\helpers\Url::to();
$tahun = !empty($_GET['tahun'])? $_GET['tahun'] : date('Y');
$urlto = !empty($_GET['tahun'])? str_replace(substr($urlto,-11),'',$urlto) : $urlto;

?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $dirfoto ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= $username . $deskripUser?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->accessRole_id != 7) {?>
<form action="#" method="get" class="sidebar-form">
<select class="form-control" id="pilihantahun">
        <?php $year = (int) date('Y'); for($i=$year;$i>=2017;$i--){?>
            <option value=<?= $i ?> ><?= $i?> </option>
        <?php }  ?>
        
    </select>
    </form>
        <?php }  ?>
        <!-- /.search form -->

        <?= \app\adminlte\widgets\Menu::widget(
            \app\usermanager\models\Menu::getMenu() 
        ) ?>

    </section>

</aside>
<?php 
// $location = '';
// if($_SESSION['tahun'] && empty($_GET['tahun'])){
//     $tahun = $_SESSION['tahun'];
//     $location = <<< JS

//         window.location.href = "$urlto" +"&tahun="+ "$tahun";  
   
// JS;
// }
$jsScript = <<< JS
    $('#pilihantahun').val('$tahun');
    
    $('#pilihantahun').on('change', function(e) {
        var href = "$urlto";
        if(href.indexOf("?") > -1)
            window.location.href = "$urlto" +"&tahun="+ $(this).val();  
        else
            window.location.href = "$urlto" +"?tahun="+ $(this).val();   
    });
JS;
    $this->registerJs("
        $jsScript;
        
        ", View::POS_END, 'run');
    

?>