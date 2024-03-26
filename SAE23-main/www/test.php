<html lang="fr">
<?php
include("./templates/header.php");

$nb_bot_req=$db->query("SELECT count(*) as BOT FROM user WHERE isBOT = 1")->fetchArray();
$nb_user_req=$db->query("SELECT count(*) as USER FROM user WHERE isBOT = 0")->fetchArray();

$dataPoints = array(
	array("label"=> "BOT", "y"=> $nb_bot_req['BOT']),
	array("label"=> "USER", "y"=> $nb_user_req['USER']),
);

$data_server = array();

$nb_message_server_req = $db->query("SELECT * FROM server ORDER BY nb_message DESC");
while ($nb_message_server = $nb_message_server_req->fetchArray()) {
    $temp = (array("label"=> $nb_message_server['guild_name'], "y"=> $nb_message_server['nb_message']));
    array_push($data_server,$temp);
}

$data_user_message = array();
$nb_user_message_req = $db->query("SELECT * FROM user ORDER BY nb_message_user DESC LIMIT 30");
while ($nb_user_message = $nb_user_message_req->fetchArray()) {
    $temp = (array("label"=> $nb_user_message['auth'], "y"=> $nb_user_message['nb_message_user']));
    array_push($data_user_message,$temp);
}

$message_by_time = array();

$oneh = 3600;

$time_now = getdate();

for ($i = 1; $i <= 24; $i++) {
    $heure2 = getdate($time_now[0] - $i*$oneh + 3600);
    $heure1 = getdate($heure2[0] - 3600);
    $req = $db->query("SELECT count(*) as nb_msg FROM message where ".$heure1[0]." < timestamp and message.timestamp < ".$heure2[0]."")->fetchArray();
    $label = "Entre {$heure2['year']}Y{$heure2['mon']}M{$heure2['mday']}J | {$heure2['hours']}:{$heure2['minutes']} & {$heure1['year']}Y{$heure1['mon']}M{$heure1['mday']}J | {$heure1['hours']}:{$heure1['minutes']}";
    $temp = (array("label"=> $label, "y"=> $req['nb_msg']));
    array_push($message_by_time,$temp);
}

function Reverse($array)
{
    return(array_reverse($array));
}

?>
<script>
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer1",
    {
        animationEnabled: true,
        title: {
            text: "Humaine VS BOT"
        },
        axisX: {
            interval: 10,
        },
        data: [{
		type: "pie",
		showInLegend: "true",
		legendText: "{label}",
		indexLabelFontSize: 16,
		indexLabel: "{label} - #percent%",
		yValueFormatString: "NB: #,##0",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
    });
chart.render();

var chart = new CanvasJS.Chart("chartContainer2",
    {
        animationEnabled: true,
        title: {
            text: "Messages par serveurs",
        },
        data: [{
		type: "pie",
		showInLegend: "true",
		legendText: "{label}",
		indexLabelFontSize: 16,
		indexLabel: "{label} - #percent%",
		yValueFormatString: "NB: #,##0",
		dataPoints: <?php echo json_encode($data_server, JSON_NUMERIC_CHECK); ?>
	}]
    });
chart.render();

var chart = new CanvasJS.Chart("chartContainer3", {
	animationEnabled: true,
	theme: "light1", // "light1", "light2", "dark1", "dark2"
	title: {
		text: "Top 30 des personnes ayant écrit des messages"
	},
    axisX:{
        labelAngle: 75,
        interval: 1
      },
	axisY: {
		title: "Nombre de messages"
	},
	data: [{
		type: "column",
		dataPoints: <?php echo json_encode($data_user_message, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();

var chart = new CanvasJS.Chart("chartContainer4", {
	animationEnabled: true,
	//theme: "light2",
	title:{
		text: "Nombre de messages par Heure, toutes les heures ces 24 dernières heures par rapport à l'actualisation du site"
	},
	axisX:{
		crosshair: {
			enabled: true,
			snapToDataPoint: true,
            
		},
        labelAngle: 75,
        interval: 1
        
	},
	axisY:{
		title: "Nombre de messages",
		includeZero: true,
		crosshair: {
			enabled: true,
			snapToDataPoint: true
		}
	},
	toolTip:{
		enabled: false
	},
	data: [{
		type: "area",
		dataPoints: <?php echo json_encode(Reverse($message_by_time), JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();

}
</script>
</head>
<body class='page_test'>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<div class='graph'>
    <div id="chartContainer1" style="height: 300px; width: 100%;"></div>
    <div id="chartContainer2" style="height: 300px; width: 100%;"></div>
</div>
<div id="chartContainer3" style="height: 400px; width: 100%;"></div>
<div id="chartContainer4" style="height: 400px; width: 100%;"></div>


</body>
</html> 