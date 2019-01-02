<?php
use app\components\Lang;
$this->title = Lang::id()? $post->title : $post->title_en;
$this->params['sidebar'] = 0;

?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Lang::id()? $post->subtitle : $post->subtitle_en;?></h3>
    </div>
    <div class="box-body">
        <?= Lang::id()? $post->content : $post->content_en;?>
    </div>
</div>


