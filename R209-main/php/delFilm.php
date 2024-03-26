<?php
$db = new SQLite3('../db/db.db');
session_start();
$uname = $_SESSION['uname'];
$fid = htmlspecialchars(trim($_GET['fid'])); #XSS protection
$fid = SQLite3::escapeString($fid); #SQL-injection protection
$db->exec("DELETE FROM panier WHERE uid IN (SELECT uid FROM compte WHERE username = '$uname') AND fid = '$fid';");
header('Refresh:0;url=panier.php');                    
?>