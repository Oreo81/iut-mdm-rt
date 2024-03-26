<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../img/favicon.ico">
    <title>Movie Planet</title>
</head>
<body>
    <header>
    <?php
        session_start();
        $dbb = new SQLite3('../db/db.db');
        $uname = $_SESSION['uname'];
        $uid = $dbb->query("SELECT uid FROM compte WHERE username = '$uname'");
        $Uid = $uid->fetchArray()['uid'];
        
        $dbb->exec("DELETE FROM panier WHERE uid ='$Uid'");

        include("../templates/header.php");

        
        ?>

   </header>
   <div class="main_page">
  <div class='alert-danger'>Ces produits ne sont pas réelement achetés, seulement supprimés du panier</div>


   </div> 
    <footer class="main_footer">
        <?php include("../templates/footer.php");?>
    </footer>
</body>
</html>

