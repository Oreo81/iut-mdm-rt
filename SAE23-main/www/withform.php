<html lang="fr">

<?php
include("./templates/header.php");
$first = $db->query("SELECT timestamp FROM message ORDER BY timestamp ASC LIMIT 1")->fetchArray();
$date = new DateTime();
$first_date =  date_format($date->setTimestamp($first[0]), 'Y-m-d');

$message_by_time = array();
?>
<body class='page_test'>

<form name="Filter" method="POST">
    From:
    <input type="date" name="dateFrom" value="<?php echo date('Y-m-d'); ?>" min="<?php echo $first_date;?>" max="<?php echo date('Y-m-d'); ?>">
    <input type="submit" name="submit" value="Show"/>
</form>

<?php
    if (isset($_POST['dateFrom'])) {
        $timestamp = strtotime($_POST['dateFrom']); 
            
        $testt =  getdate($timestamp);
        $message_by_time = array();

        $oneh = 3600;

        $time_now = getdate();

        for ($i = 1; $i <= 24; $i++) {
            $heure2 = getdate($timestamp + $i*$oneh );
            $heure1 = getdate($heure2[0] - 3600);


            $req = $db->query("SELECT count(*) as nb_msg FROM message where ".$heure1[0]." < timestamp and message.timestamp < ".$heure2[0]."")->fetchArray();
            $label = "Entre {$heure1['hours']}:{$heure1['minutes']} & {$heure2['hours']}:{$heure2['minutes']}";
            $temp = (array("label"=> $label, "y"=> $req['nb_msg']));
            array_push($message_by_time,$temp);
        }
        echo "<script>";
        echo "window.onload = function () {";
            echo "var chart = new CanvasJS.Chart('chartContainer4', {";
            echo "animationEnabled: true,";

            echo "title:{";
                echo "    text: 'Données du {$testt['mday']}/{$testt['mon']}/{$testt['year']}'";
                echo "},";
                echo " axisX:{";
                    echo "    crosshair: {";
                        echo "        enabled: true,";
                        echo "         snapToDataPoint: true,";
                    
                        echo "     },";
                        echo "     labelAngle: 75,";
                        echo "     interval: 1";
                
                        echo " },";
                        echo " axisY:{";
                            echo "    title: 'Nombre de messages',";
                            echo "    includeZero: true,";
                            echo "    crosshair: {";
                                echo "        enabled: true,";
                                echo "        snapToDataPoint: true";
                                echo "   }";
                                echo "},";
                                echo "toolTip:{";
                                    echo "    enabled: false";
                                    echo " },";
                                    echo "data: [{";
                                        echo " type: 'area',";
                                        echo "dataPoints: ".json_encode($message_by_time, JSON_NUMERIC_CHECK)."";
                                        echo "}]";
                                        echo "});";
                                        echo "chart.render();";

                                        echo "}";
                                        echo "</script>";


        echo "<script src='https://canvasjs.com/assets/script/canvasjs.min.js'></script>";
        echo "<div id='chartContainer4' style='height: 400px; width: 100%;'></div>";
    }
?>
</body>
</html> 