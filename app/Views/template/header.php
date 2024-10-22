<?php
     
     $dataPoints = array(
         
         array("label"=> "Masuk", "y"=> 573),
         array("label"=> "Keluar", "y"=> 126)
     );
         
     ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/css/style.css">
</head>
<script>
     window.onload = function () {
      
     var chart = new CanvasJS.Chart("chartContainer", {
         animationEnabled: true,
         exportEnabled: false,
         title:{
             text: ""
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
<body class="bg-gray-800 text-yellow-200 container mx-auto">