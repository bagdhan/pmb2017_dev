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
<h2>Rekap Pleno 2 Per Fakultas (Status Penerimaan) Program S3
</h2>
<div class="row tableroom">
    <table class="table table-bordered">
        <thead>
        <tr align='center'>
            <th rowspan="2" align='center'>No</th>
            <th rowspan="2"><?= $name?></th>
            <th rowspan="2" align='center'>Hasil Pleno 1</th>
            <th colspan="6" align='center'>Hasil Pleno 2</th>
            <th rowspan="2" align='center'>Sub Total</th>
            <th colspan="3" align='center'>Limpahan</th>
            <th rowspan="2" align='center'>Status Pleno (%)</th>
        </tr>
        <tr align='center'>
            <th align='center'>B</th>
            <th align='center'>%</th>
            <th align='center'>P</th>
            <th align='center'>%</th>
            <th align='center'>T</th>
            <th align='center'>%</th>
            <th align='center'>B</th>
            <th align='center'>P</th>
            <th align='center'>T</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $i = 1;
        foreach ($data as $idx => $d) {
            if ($idx != 'tot' && $d[3]) {
                echo "<tr>
                      <td align='center'>$i</td>
                      <td>$d[2]</td>
                      <td align='center'>$d[3]</td>
                      <td align='center'>$d[4]</td>
                      <td align='center'>$d[5]</td>
                      <td align='center'>$d[6]</td>
                      <td align='center'>$d[7]</td>
                      <td align='center'>$d[8]</td>
                      <td align='center'>$d[9]</td>
                      <td align='center'>$d[10]</td>
                      <td align='center'>$d[12]</td>
                      <td align='center'>$d[13]</td>
                      <td align='center'>$d[14]</td>
                      <td align='center'>$d[11]</td>
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
                      <td align='center'><strong>" . $data['tot'][9] . "</strong></td>
                      <td align='center'><strong>" . $data['tot'][10] . "</strong></td>
                      <td align='center'><strong>" . $data['tot'][12] . "</strong></td>
                      <td align='center'><strong>" . $data['tot'][13] . "</strong></td>
                      <td align='center'><strong>" . $data['tot'][14] . "</strong></td>
                      <td align='center'><strong>" . $data['tot'][11] . "</strong></td>
                    </tr>";
        ?>
        </tbody>
    </table>
</div>            