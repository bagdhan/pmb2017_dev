    <div class="row">
    <div class="col-lg-12" >
    <h3>Rekap Data Per Fakultas</h3>
        <table style="width:100%;" class='table' id='rekapfak'>
            <thead>
                <tr>
                    <th rowspan="3">No</th>
                    <th rowspan="3">Fakultas</th>
                    <th colspan="7">Pelamar</th>
                    <th colspan="7">Masuk Pleno</th>
                    
                </tr>
                <tr>
                    <th colspan="2">S2</th>
                    <th rowspan="2">Sub Total</th>
                    <th colspan="2">S3</th>
                    <th rowspan="2">Sub Total</th>
                    <th rowspan="2">Jumlah Total</th>
                    <!-- Masuk Pleno -->
                    <th colspan="2">S2</th>
                    <th rowspan="2">Sub Total</th>
                    <th colspan="2">S3</th>
                    <th rowspan="2">Sub Total</th>
                    <th rowspan="2">Jumlah Total</th>
                </tr>
                 <tr>
                    <th>L</th>
                    <th>P</th>
                    <th>L</th>
                    <th>P</th>
                    <!-- Masuk Pleno -->
                    <th>L</th>
                    <th>P</th>
                    <th>L</th>
                    <th>P</th>
                </tr>
            </thead>
            <tbody>
            <?php $no=1; $jump2 = 0; $jumw2 = 0; $jump3 = 0; $jumw3 = 0; $jump2p = 0; $jumw2p = 0; $jump3p = 0; $jumw3p = 0; 
            foreach($fakultas as $fak){ ?>
            <tr>
                <td><?php echo $no;?></td>
                <td><?php echo $fak['inisial'];?></td>
                <td><?php echo $p2 = $pria[$fak['inisial']]['2']['jumlah'];?></td>
                <td><?php echo $w2 = $wanita[$fak['inisial']]['2']['jumlah'];?></td>
                <td><?php echo $s2 = $p2 + $w2;?></td>  
                <td><?php echo $p3 = $pria[$fak['inisial']]['3']['jumlah'];?></td>
                <td><?php echo $w3 = $wanita[$fak['inisial']]['3']['jumlah'];?></td>
                <td><?php echo $s3 = $p3 + $w3;?></td>
                <td><?php echo ($s2 + $s3);?></td>
                <!-- Masuk Pleno -->
                <td><?php echo $p2p = $pria[$fak['inisial']]['2']['pleno'];?></td>
                <td><?php echo $w2p = $wanita[$fak['inisial']]['2']['pleno'];?></td>
                <td><?php echo $s2p = $p2p + $w2p;?></td>  
                <td><?php echo $p3p = $pria[$fak['inisial']]['3']['pleno'];?></td>
                <td><?php echo $w3p = $wanita[$fak['inisial']]['3']['pleno'];?></td>
                <td><?php echo $s3p = $p3p + $w3p;?></td>
                <td><?php echo ($s2p + $s3p);?></td>
            </tr>
            <?php $no++; $jump2+=$p2; $jumw2+=$w2; $jump3+=$p3; $jumw3+=$w3; $jump2p+=$p2p; $jumw2p+=$w2p; $jump3p+=$p3p; $jumw3p+=$w3p;}?>
            <tr>
                <td>11</td>
                <td>Belum Memilih</td>
                <td><?php echo $p2 = $allpria['nomayor']['2']['jumlah'];?></td>
                <td><?php echo $w2 = $allwanita['nomayor']['2']['jumlah'];?></td>
                <td><?php echo $s2 = $p2 + $w2;?></td>  
                <td><?php echo $p3 = 0;?></td>
                <td><?php echo $w3 = 0;?></td>
                <td><?php echo $s3 = $p3 + $w3;?></td>
                <td><?php echo ($s2 + $s3);?></td>
                <!-- Masuk Pleno -->
                <td>0</td>
                <td>0</td>
                <td>0</td>  
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
            </tr>
            </tbody>
            <tfoot>
                <tr>
                <td></td>
                <td><strong>Jumlah</strong></td>
                <td><strong><?php echo $p2 = $jump2 + $p2;?></strong></td>
                <td><strong><?php echo $w2 = $jumw2 + $w2;?></strong></td>
                <td><strong><?php echo $s2 = $p2 + $w2;?></strong></td>
                <td><strong><?php echo $p3 = $jump3 + $p3;?></strong></td>
                <td><strong><?php echo $w3 = $jumw3 + $w3;?></strong></td>
                <td><strong><?php echo $s3 = $p3 + $w3;?></strong></td>  
                <td><strong><?php echo ($s2 + $s3);?></strong></td>
                <!-- Masuk Pleno -->
                <td><strong><?php echo $p2 = $jump2p;?></strong></td>
                <td><strong><?php echo $w2 = $jumw2p;?></strong></td>
                <td><strong><?php echo $s2 = $p2 + $w2;?></strong></td>
                <td><strong><?php echo $p3 = $jump3p;?></strong></td>
                <td><strong><?php echo $w3 = $jumw3p;?></strong></td>
                <td><strong><?php echo $s3 = $p3 + $w3;?></strong></td>  
                <td><strong><?php echo ($s2 + $s3);?></strong></td>
            </tr>
            </tfoot>
        </table>
        </div>
    </div>

<br/>
    <div class="row">
    <div class="col-lg-12" >
    <h3>Rekap Data Per Rencana Biaya Pendidikan</h3>
        <table style="width:100%;" class='table' id='rekapbi'>
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Rencana Biaya Pendidikan</th>
                    <th colspan="2">S2</th>
                    <th rowspan="2">Sub Total</th>
                    <th colspan="2">S3</th>
                    <th rowspan="2">Sub Total</th>
                    <th rowspan="2">Jumlah Total</th>
                </tr>
                 <tr>
                    <th>L</th>
                    <th>P</th>
                    <th>L</th>
                    <th>P</th>
                </tr>
            </thead>
            <tbody>
            
            <tr>
                <td>1</td>
                <td>Beasiswa Kerjasama IPB</td>
                <td><?php echo $p2 = $allpria['kerjasama']['2']['jumlah'];?></td>
                <td><?php echo $w2 = $allwanita['kerjasama']['2']['jumlah'];?></td>
                <td><?php echo $kerjasamas2 = $p2 + $w2;?></td>  
                <td><?php echo $p3 = $allpria['kerjasama']['3']['jumlah'];?></td>
                <td><?php echo $w3 = $allwanita['kerjasama']['3']['jumlah'];?></td>
                <td><?php echo $kerjasamas3 = $p3 + $w3;?></td>
                <td><?php $allp2 = $p2; $allp3 = $p3; $allw2 = $w2; $allw3 = $w3; echo $totkerjasama = ($kerjasamas2 + $kerjasamas3);?></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Beasiswa Lainnya</td>
                <td><?php echo $p2 = $allpria['lain']['2']['jumlah'];?></td>
                <td><?php echo $w2 = $allwanita['lain']['2']['jumlah'];?></td>
                <td><?php echo $lainnyas2 = $p2 + $w2;?></td>  
                <td><?php echo $p3 = $allpria['lain']['3']['jumlah'];?></td>
                <td><?php echo $w3 = $allwanita['lain']['3']['jumlah'];?></td>
                <td><?php echo $lainnyas3 = $p3 + $w3;?></td>
                <td><?php $allp2 += $p2; $allp3 += $p3; $allw2 += $w2; $allw3 += $w3; echo $totlainnya=($lainnyas2 + $lainnyas3);?></td>
            </tr>
            <tr>
                <td>3</td>
                <td>Biaya Sendiri</td>
                <td><?php echo $p2 = $allpria['sendiri']['2']['jumlah'] ;?></td>
                <td><?php echo $w2 = $allwanita['sendiri']['2']['jumlah'];?></td>
                <td><?php echo $sendiris2 = $p2 + $w2;?></td>  
                <td><?php echo $p3 = $allpria['sendiri']['3']['jumlah'];?></td>
                <td><?php echo $w3 = $allwanita['sendiri']['3']['jumlah'];?></td>
                <td><?php echo $sendiris3 = $p3 + $w3;?></td>
                <td><?php $allp2 += $p2; $allp3 += $p3; $allw2 += $w2; $allw3 += $w3; echo $totsendiri=($sendiris2 + $sendiris3);?></td>
            </tr>
            
            </tbody>
            <tfoot>
                <tr>
                <td></td>
                <td><strong>Jumlah</strong></td>
                <td><strong><?php echo $allp2;?></strong></td>
                <td><strong><?php echo $allw2;?></strong></td>
                <td><strong><?php echo $allp2 + $allw2;?></strong></td>
                <td><strong><?php echo $allp3;?></strong></td>
                <td><strong><?php echo $allw3;?></strong></td>
                <td><strong><?php echo $allp3 + $allw3;?></td>  
                <td><strong><?php echo ($totkerjasama + $totlainnya + $totsendiri);?></strong></td>
            </tr>
            </tfoot>
        </table>
        <br/>
        <span>*) Data tidak termasuk mahasiswa yang menunda tahun sebelumnya.</span>
        </div>
    </div>