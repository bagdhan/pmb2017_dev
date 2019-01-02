<?php
/**
 * Created by PhpStorm.
 * User: wisard17
 * Date: 4/4/2017
 * Time: 10:39 AM
 *
 * @var $data array
 */


echo "<table class='table table-bordered'><tr><th>id</th><th>nama</th><th>username</th><th>email</th><th>action</th></tr>";

foreach ($data as $datum) {
    echo "<tr><td>$datum[id]</td><td>$datum[nama]</td><td>$datum[username]</td><td>$datum[email]</td><td>" .
        \yii\helpers\Html::a('tambah NoPendaftaran', ['/api/send-nopen', 'idOrang' => $datum['id'], 'paketId' => 1], ['class' => 'btn btn-info'])
        . "</td></tr>";
}

echo "</table>";