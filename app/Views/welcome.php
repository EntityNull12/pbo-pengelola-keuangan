<?php
     
     $dataPoints = array(
         array("label"=> "Pendapatan", "y"=> 590),
         array("label"=> "Pengeluaran", "y"=> 261)
     );
         
     ?>
     <!DOCTYPE HTML>
     <html>
     <head>  
     <script>
     window.onload = function () {
      
     var chart = new CanvasJS.Chart("chartContainer", {
         animationEnabled: true,
         exportEnabled: false,
         title:{
             text: "Pengelola keuangan"
         },
         subtitles: [{
             text: ""
         }],
         data: [{
             type: "pie",
             showInLegend: "true",
             legendText: "{label}",
             indexLabelFontSize: 16,
             indexLabel: "{label} - #percent%",
             yValueFormatString: "Rp#,##0",
             dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
         }]
     });
     chart.render();
      
     }
     </script>
     </head>
     <body>
     <div id="chartContainer" style="height: 370px; width: 100%;"></div>
     <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
     </body>
     </html>                              