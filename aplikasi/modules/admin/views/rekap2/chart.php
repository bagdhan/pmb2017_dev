<?php 
use yii\googlechart\GoogleChart;
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
       
        // w0.setAction({
        //   id: 'detail',
        //   text: 'See detail',
        //   action: function() {
        //     selection = w0.getSelection();
        //     switch (selection[0].row) {
        //       case 0: $("#myModal").modal("show"); break;
        //       case 1: alert('Feynman Lectures on Physics'); break;
        //       case 2: alert('Numerical Recipes in JavaScript'); break;
        //       case 3: alert('Truman'); break;
        //       case 4: alert('Freakonomics'); break;
        //       case 5: alert('The Mezzanine'); break;
        //       case 6: alert('The Color of Magic'); break;
        //       case 7: alert('The Law of Superheroes'); break;
        //     }
        //   }
        // });
    // google.visualization.events.addListener(w0, 'select', selectHandler);

      }

      
      // function selectHandler() {
      //     var selection = w0.getSelection();
      //     var message = '';

      //     for (var i = 0; i < selection.length; i++) {
      //       var item = selection[i];
      //       if (item.row != null && item.column != null) {
      //         message += '{row:' + item.row + ',column:' + item.column + '}';
      //       } else if (item.row != null) {
      //         message += '{row:' + item.row + '}';
      //       } else if (item.column != null) {
      //         message += '{column:' + item.column + '}';
      //       }
      //     }
      //     if (message == '') {
      //       message = 'nothing';
      //     }
      //     // alert('You selected ' + message);
      //     switch (item.row) {
      //         case 0: alert('Ender\'s Game'); break;
      //         case 1: alert('Feynman Lectures on Physics'); break;
      //         case 2: alert('Numerical Recipes in JavaScript'); break;
      //         case 3: alert('Truman'); break;
      //         case 4: alert('Freakonomics'); break;
      //         case 5: alert('The Mezzanine'); break;
      //         case 6: alert('The Color of Magic'); break;
      //         case 7: alert('The Law of Superheroes'); break;
      //       }
      //   }
    </script>

<div class="row">
<div class="col-lg-12" >
        <div class="col col-md-5">
            <?php 
                echo GoogleChart::widget(array('visualization' => 'PieChart',
                        'data' => $faks2,
                        'options' => [
                            'title' => 'Sebaran Calon Mahasiswa S2 per Fakultas', 
                            'width'=>'650',
                            'height'=>'375',
                            'is3D'=> true,
                            // 'tooltip'=>['trigger'=>'selection']
                        ]));
            ?>
        </div>
        <div class="col col-md-5">
            <?php
                echo GoogleChart::widget(array('visualization' => 'PieChart',
                        'data' => $faks3,
                        'options' => [
                            'title' => 'Sebaran Calon Mahasiswa S3 per Fakultas', 
                            'width'=>'650',
                            'height'=>'375',
                            'is3D'=> true
                        ]));
            ?>
        </div>
        <div class="col col-md-11"> 
            <?php
                echo GoogleChart::widget(array('visualization' => 'PieChart',
                        'data' => $fak,
                        'options' => [
                            'title' => 'Sebaran Calon Mahasiswa S2 & S3 per Fakultas',
                            'width'=>'1200',
                            'height'=>'500',
                            'is3D'=> true,
                        ]));
            ?>
        </div>
      </div>
    </div>
   
