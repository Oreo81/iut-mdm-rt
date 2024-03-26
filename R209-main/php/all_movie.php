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
                $res = $db->query("SELECT * FROM produits");
                    
                echo "<div class='main_tab'>";
                while ($row = $res->fetchArray()) {
                    echo "<div class='film'>";
                        echo "<div class='image'>";
                            echo "<a href='fiche.php?movie={$row['fid']}' class='link'>";
                            echo "<center><img src='../img/film_img/{$row['fid']}.jpg' alt='{$row['nom']}' width='200'><br></a>";
                        echo"</div>";
                        echo"<div class='title'>";
                            echo "<p>";
                                echo "<a href='fiche.php?movie={$row['fid']}'> {$row['nom']}</a>";
                            echo"</p>";
                        echo"</div>";
                    echo"</div>";
                }  
                echo"</div>";
            ?>
        </div>     
    </div>  
    <footer class="main_footer">
        <?php include("../templates/footer.php");?>
    </footer>
</body>