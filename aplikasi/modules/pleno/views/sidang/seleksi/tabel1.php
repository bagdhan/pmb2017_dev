<?php

use yii\web\View;
use yii\helpers\Html;

switch (Yii::$app->user->identity->accessRole_id) {
    case 3:
        $name = 'Fakultas';
        break;
    case 4:
        $name = 'Program Studi';
        break;
    case 5:
        $name = 'Program Studi';
        break;
    case 6:
        $name = 'Program Studi';
        break;
    default:
        $name = '';

}
?>
<div class="row tableroom">
    <table class="table table-bordered">
        <thead>
        <tr align='center'>
            <th rowspan="2" align='center'>No</th>
            <th rowspan="2"><?= $name?></th>
            <th colspan="2">Hasil Pleno 1</th>
            <th rowspan="2" align='center'>Sub Total</th>
            <th colspan="2">Hasil Seleksi</th>

            <th rowspan="2" align='center'>Sub Total</th>
            <th colspan="2">Limpahan</th>
            <th rowspan="2" align='center'>Status Seleksi (%)</th>
        </tr>
        <tr align='center'>
            <th align='center'>S2</th>
            <th align='center'>S3</th>
            <th align='center'>S2</th>
            <th align='center'>S3</th>
            <th align='center'>Jumlah</th>
            <th align='center'>Sudah Distatuskan</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $i = 1;
        foreach ($data as $idx => $d) {
            if ($idx != 'tot') {
                echo "<tr>
                      <td align='center'>$i</td>
                      <td>$d[2]</td>
                      <td align='center'>$d[3]</td>
                      <td align='center'>$d[4]</td>
                      <td align='center'>$d[5]</td>
                      <td align='center'>$d[6]</td>
                      <td align='center'>$d[7]</td>
                      <td align='center'>$d[8]</td>
                      <td align='center'>$d[10]</td>
                      <td align='center'>$d[11]</td>
                      <td align='center'>$d[9]</td>
                    </tr>";
                $i++;
            }

        };
        echo "<tr>
                      <td align='center'></td>
                      <td><strong>Jumlah Total</strong></td>
                      <td align='center'><strong>" . $data['tot'][3] . "</strong></td>
                      <td align='center'><strong>" . $data['tot'][4] . "</strong></td>
                      <td align='center'><strong>" . $data['tot'][5] . "</strong></td>
                      <td align='center'><strong>" . $data['tot'][6] . "</strong></td>
                      <td align='center'><strong>" . $data['tot'][7] . "</strong></td>
                      <td align='center'><strong>" . $data['tot'][8] . "</strong></td>
                      <td align='center'><strong>" . $data['tot'][10] . "</strong></td>
                      <td align='center'><strong>" . $data['tot'][11] . "</strong></td>
                      <td align='center'><strong>" . $data['tot'][9] . "</strong></td>
                    </tr>";
        ?>
        </tbody>
    </table>
</div>            