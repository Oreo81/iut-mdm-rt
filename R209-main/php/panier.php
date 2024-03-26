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
        include("../templates/header.php");
      ?>

   </header>
   <div class="main_page">
   <?php
      $db = new SQLite3('../db/db.db');

      if(isset($_SESSION['uname'])) {
         $uname = $_SESSION['uname'];

         $Uid = $db->query("SELECT uid FROM compte WHERE username = '$uname'");
         $ListUid = $Uid->fetchArray()['uid'];

         $panier = $db->query("SELECT fid,nom,prix FROM produits WHERE fid IN (SELECT fid FROM panier WHERE uid = '$ListUid');");

         $nbProd = $db->query("SELECT qte FROM panier WHERE fid IN (SELECT fid FROM panier WHERE uid = '$ListUid');");
         $for_test = $nbProd;
         $test = $for_test->fetchArray();

         if(is_bool($test)){
            $NbProd = 0;
            echo "<div class='alert-danger'> Panier Vide </div>";
            
         }
         elseif (is_array($test)){

            echo"<fieldset>";
               echo"<legend><h1>Panier de ".$uname.":</h1></legend>";
               $prix_tot = 0;
               $nb = 0;
               echo "<div class=panier_boite>";
               while($ListPanier = $panier->fetchArray()){
                  $fid_current = $ListPanier['fid'];
                  $qte_req = $db->query("SELECT qte FROM panier WHERE uid = $ListUid and fid = $fid_current");
                  $qte = $qte_req->fetchArray();
                     echo "<div class=panier_boite_left>";
                        echo "<center><a href='fiche.php?movie={$ListPanier['fid']}'><img src='../img/film_img/{$ListPanier['fid']}.jpg' height=300px></center>";  
                     echo "</div>";
                     echo "<div class=panier_boite_right>";
                        echo "<ul>";
                           echo"<li><h2>{$ListPanier['nom']} : {$qte['qte']}</h2><br><br>";
                                 $prix_tot += ($ListPanier['prix']*$qte['qte']);
                                 $nb += (1*$qte['qte']);
                           echo"<li><a href='delFilm.php?fid={$ListPanier['fid']}'><img src='../img/bin.png' style='height:30px; vertical-align:middle'</a>Supprimer l'achat</li>";
                           echo"<li><a href='AjouterQte.php?fid={$ListPanier['fid']}'><img src='../img/plus.png' alt='Ajouter' style='height:30px; vertical-align:middle'></a>Ajouter une quantité</li>";
                           echo"<li><a href='SuppQte.php?fid={$ListPanier['fid']}'><img src='../img/moins.png' alt='Supprimer' style='height:30px; vertical-align:middle'></a>Supprimer une quantité</li>";
                        echo "</ul>";
                     echo "</div>";
               }     
               echo "</div>";
                     echo"<h2 style='text-align: center; color: #fff'>Total: {$nb} Articles - {$prix_tot} €</h2>";
                     echo"<div class='acheter'><center><form action='' method='post'><input type='submit' name='valider' value='Valider'></form></center></div>";
            echo"</fieldset>";

            if(ISSET($_POST['valider'])){
               echo"<audio autoplay><source src='../img/bubble_pop_1.mp3' type='audio/mpeg'></audio>";
               header("Refresh:1;url=fin_commande.php");
            } 
         }
      }
      else  {  
         echo "<div class='no_login'> <a href='login.php'>Connectez-vous</a> pour voir votre panier</div>";
      }   
   ?>
   </div> 
    <footer class="main_footer">
        <?php include("../templates/footer.php");?>
    </footer>
</body>
</html>