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
        <div class="main_container">
            <?php 
                $db = new SQLite3("../db/db.db");
                $cat = htmlspecialchars(trim($_GET['categorie'])); #XSS protection
                $cat = SQLite3::escapeString($cat); #SQL-injection protection
                
                $res1 = $db->query("SELECT produits.fid,produits.nom FROM produits,info_film WHERE produits.fid in (SELECT fid from info_film WHERE categorie1 = (SELECT cid FROM categorie where cnom = '$cat' ) or categorie2 = (SELECT cid FROM categorie where cnom = '$cat')) and produits.fid = info_film.fid");
                $res2 = $db->query("SELECT produits.fid,produits.nom FROM produits,info_film WHERE produits.fid in (SELECT fid from info_film WHERE categorie1 = (SELECT cid FROM categorie where cnom = '$cat' ) or categorie2 = (SELECT cid FROM categorie where cnom = '$cat')) and produits.fid = info_film.fid");
            ?>  
            <?php
            $test = $res1->fetchArray();

            $cate = $db->query("SELECT * FROM categorie");
            $list_cat = [];

            while ($tt1 = $cate->fetchArray()) {
                $list_cat[] = $tt1['cnom'];
            }
            if (in_array($cat ,$list_cat)){
                if (is_array($test)){    
                    while ($row = $res2->fetchArray()) {
                        echo "<div class='film'>";
                            echo "<div class='image'>";
                                echo "<a href='fiche.php?movie={$row['fid']}' class='link'>";
                                echo "<center><img src='../img/film_img/{$row['fid']}.jpg' alt='{$row['nom']}' width='200'><br></a>";
                            echo"</div>";
                            echo "<div class='title'>";
                                echo "<p>";
                                    echo "<a href='fiche.php?movie={$row['fid']}'> {$row['nom']}</a>";
                                echo "</p>";
                            echo "</div>";
                        echo "</div>";
                    }  
                }
                else {
                    echo "<div class='alert-danger'> /!\ Aucun film trouvé dans cette cathégorie</div>";
                }
            }
            elseif (is_bool($test)){
                echo "<div class='alert-danger'> /!\ Catégorie inexistante</div>";
            }
            ?>
        </div>
    </div>  
    <footer class="main_footer">
        <?php include("../templates/footer.php");?>
    </footer>
</body>