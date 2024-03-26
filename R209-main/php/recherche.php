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
        include("../templates/header.php");?>
    </header>
    <div class="main_page">
        <?php
            $db = new SQLite3('../db/db.db');

            $Recherche = trim(htmlspecialchars($_GET['recherche'])); # XSS protection
            $Recherche = SQLite3::escapeString($Recherche); #SQL-injection protection
                
            $searchRq = $db->query("SELECT fid,nom FROM produits WHERE nom LIKE '%$Recherche%'");
            $isEmpty = $db->query("SELECT * FROM produits WHERE EXISTS(SELECT nom FROM produits WHERE nom LIKE '%$Recherche%')");
            $ListIsEmpty = $isEmpty->fetchArray();

            echo "<div>";
                echo "<div class='main_container'>";
                    echo"<fieldset>";
                        echo"<legend><h1>".$Recherche." :</h1></legend>";
                
                    if (empty($ListIsEmpty)){   
                        echo"<div class='film_search'>";
                            echo "<div class='alert-danger'>Aucun élément trouvé</div>";
                        echo"</div>";
                    }
                    else{
                        while($ListSearchRq = $searchRq->fetchArray()){   
                            echo "<div class='film'>";
                                echo "<div class='image'>";
                                    echo "<a href='fiche.php?movie={$ListSearchRq['fid']}' class='link'>";
                                    echo "<center><img src='../img/film_img/{$ListSearchRq['fid']}.jpg' alt='{$ListSearchRq['nom']}' width='200'><br></a>";
                                echo"</div>";
                                echo"<div class='title'>";
                                    echo "<p>";
                                        echo "<a href='fiche.php?movie={$ListSearchRq['fid']}'> {$ListSearchRq['nom']}</a>";
                                    echo"</p>";
                                echo"</div>";
                            echo"</div>";
                        }
                    }
                    
                    echo"</fieldset>";
                echo"</div>";
            echo"</div>";
        ?>
        </div>
        <footer class="main_footer">
            <?php include("../templates/footer.php"); ?>
        </footer>
    </body>
</html>