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
        $db = new SQLite3("../db/db.db");
        $res = $db->query("SELECT * FROM categorie");
        ?>

        <?php
        echo "<div class='box'>";
            echo "<table>";
                echo "<thead>";
                    echo "<tr>";
                        echo "<th><a href='../php/all_movie.php'>Tout les films</a><br></th>";
                    echo "</tr>";
                echo "</thead>";
            echo "</table>";
        echo "</div>";
            echo "<div class='main_tab'>";  
                
                        while ($row = $res->fetchArray()) {
                            echo "<div class='box'>";
                                echo "<table>";
                                    echo "<thead>";
                                        echo "<tr>";
                                            echo "<th><a href='../php/categorie.php?categorie={$row['cnom']}'>{$row['cnom']}</a></th>";
                                        echo "</tr>";
                                    echo "</thead>";
                                echo "</table>";
                            echo "</div>";
                        }  
            echo "</div>";
        ?>   
    </div>  
    <footer class="main_footer">
        <?php include("../templates/footer.php");?>
    </footer>
</body>