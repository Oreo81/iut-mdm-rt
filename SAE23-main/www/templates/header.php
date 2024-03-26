<?php
require('./function.php');
date_default_timezone_set('Europe/Paris');
$db = new SQLite3("./db/db_discord.db");

$heure = $db->query("SELECT timestamp FROM heure WHERE id = 1")->fetchArray();
$first = $db->query("SELECT timestamp FROM message ORDER BY timestamp ASC LIMIT 1")->fetchArray();

$time_now = getdate();
$time_last_update = getdate($heure['timestamp']);
$time_fisrt = getdate($first['timestamp']);

echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<link rel='stylesheet' href='./css/style.css'>";
echo "</head>";

echo "<div class= 'top'>";
    echo "<div class= 'left'>";
        echo "Heure sur le site (UTC+2) : (y:m:j)|(h:m:s) <b>{$time_now['year']}:{$time_now['mon']}:{$time_now['mday']} | {$time_now['hours']}:{$time_now['minutes']}:{$time_now['seconds']}</b> <br>";
        echo "Heure dernière actualisation BD (UTC+2): (y:m:j)|(h:m:s) <b>{$time_last_update['year']}:{$time_last_update['mon']}:{$time_last_update['mday']} | {$time_last_update['hours']}:{$time_last_update['minutes']}:{$time_last_update['seconds']}</b><br>";
        echo "Heure première donné (UTC+2): (y:m:j)|(h:m:s) <b>{$time_fisrt['year']}:{$time_fisrt['mon']}:{$time_fisrt['mday']} | {$time_fisrt['hours']}:{$time_fisrt['minutes']}:{$time_fisrt['seconds']}</b><br>";
    echo "</div>";
    echo "<div class= 'mid'>";
        echo "<h1>mqtt.lgdl.org</h1>";
        // echo "<p><a href='./db/db_discord.db'>Download DB</a> | <a href='./'>Main</a> | <a href='./test.php'>test</a> | <a href='./withform.php'>withform</a></p>";
        echo "<p><a href='./'>Main</a> | <a href='./test.php'>test</a> | <a href='./withform.php'>withform</a></p>";
        echo "<p>Taile de la BDD: ".FileSizeConvert(filesize('./db/db_discord.db'))."</p>";
    echo "</div>";
    echo "<div class= 'right'>";
            $serv = $db->query("SELECT COUNT(*) as nb FROM server");
            while ($row_serv = $serv->fetchArray()) {
                echo "Nombre de serveur: {$row_serv['nb']} <br>";}

            $user = $db->query("SELECT COUNT(*) as nb FROM user");
            while ($row_user = $user->fetchArray()) {
                echo "Nombre d'utilisateur: {$row_user['nb']} <br>";}

            $msg = $db->query("SELECT COUNT(*) as nb FROM message");
            while ($row_msg = $msg->fetchArray()) {
                echo "Nombre de message: {$row_msg['nb']} <br>";}

            $chan = $db->query("SELECT COUNT(*) as nb FROM channel");
            while ($row_chan = $chan->fetchArray()) {
                echo "Nombre de channel: {$row_chan['nb']}";}

    echo "</div>";
echo "</div>";

?>

            