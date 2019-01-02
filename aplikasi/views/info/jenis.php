<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 4/4/2017
 * Time: 11:18 AM
 */
use app\components\Lang;

$this->title = Lang::id()? $post->title : $post->title_en;

?>
<div class="box box-default">
  <div class="box-body">
<section id="introduction1">
    <?= Lang::id()? $post->content : $post->content_en;?>
</section>

</div><!-- /.box-body -->
    </div><!-- /.box -->




