<?php
date_default_timezone_set('Europe/Paris');
$db = new SQLite3("./db/db_discord.db");

$nb_bot = 0;
$nb_user = 0;
$top = 0;

if(isset($_GET['nb_bot'])){
	if($_GET['nb_bot'] == 1){
		$nb_bot = 1;
	}
}

if(isset($_GET['nb_user'])){
	if($_GET['nb_user'] == 1){
		$nb_user = 1;
	}
}

if(isset($_GET['top'])){
	$top = $_GET['top'];
}

$data = array();

if ( $nb_bot == 1) {
    $nb_bot_req=$db->query("SELECT count(*) as BOT FROM user WHERE isBOT = 1")->fetchArray();
	$data["nb_bot"] = $nb_bot_req['BOT'];
}

if ( $nb_user == 1){
	$nb_user_req=$db->query("SELECT count(*) as USER FROM user WHERE isBOT = 0")->fetchArray();
	$data["nb_user"] = $nb_user_req['USER'];
}

if ( $top != 0 && is_numeric($top)){
	$data_user_message = array();
	$nb_user_message_req = $db->query("SELECT * FROM user ORDER BY nb_message_user DESC LIMIT $top");
	$top = 0;
	while ($nb_user_message = $nb_user_message_req->fetchArray()) {
		$data_user_message[$nb_user_message['auth']] = $nb_user_message['nb_message_user'];
		$top +=1;
	}
	$data["top_{$top}"] = $data_user_message;
}

else {
}


header('Content-type: application/json');
echo json_encode( $data );