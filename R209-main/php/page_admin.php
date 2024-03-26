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
    <style>
        .add-film, .del-film{
            /* width:100%;
            height: 50%; */
            /* background-color : black; */
            color: white;
            text-align: center;
            display: grid;
            grid-template-columns: repeat(1, auto);
        }

        .inputt{
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

    </style>
</head>
<div class="main_page">
    <div class="main_container">
        <?php 
            if(isset($_SESSION['uname']))  
            {  
                $uname = $_SESSION['uname'];
                if ($uname != 'admin') {
                    echo "<center><img src='../img/ratz.jpg' alt='Trop curieux' style='width:100%;height:100%;'></center>";
                    echo "</div>";
                    echo "<footer class='main_footer'>";
                    include("../templates/footer.php");
                    echo "</footer>";
                    exit();
                }
            }
            else {
                echo "<center><img src='../img/ratz.jpg' alt='Trop curieux' style='width:33%;height:33%;margin-top:10%;'></center>";
                echo "</div>";
                echo "<footer class='main_footer'>";
                include("../templates/footer.php");
                echo "</footer>";
                exit();
            }
        ?>

        <?php
            $db->close();
            $conn = new PDO('sqlite:../db/db.db') or die("Impossible d'ouvir la base sqlite!");
            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $conn2 = new SQLite3('../db/db.db') or die("Impossible d'ouvir la base sqlite!");
            $category = $conn->query("SELECT * FROM categorie;");
        ?>

        <?php
        echo"<div class='admin-add-div'>";
            echo"<form action='' method='post'  enctype='multipart/form-data'>";
                echo"<div class='add-film'>";
                    echo"<div class='gg'>";
                        echo"<h1>Ajouter : </h1>";
            
                        echo"<div class='textbox1'>";
                            echo"<br>";
                            echo"<label>Titre :</label>";
                            echo"<i class='title' aria-hidden='true'></i>";
                            echo"<input class ='inputt' type='text' placeholder='Titre' name='title' value='' required='required'>";
                        echo"</div>";
                        
                        echo"<div class='textbox1'>";
                        echo"<br>";
                            echo"<label>Synopsis :</label>";
                            echo"<i class='synopsis' aria-hidden='true'></i>";
                            echo"<input class ='inputt' type='text' placeholder='Synopsis' name='synopsis' value='' required='required'>";
                        echo"</div>";
                        
                        echo"<div class='textbox1'>";
                            echo"<br>";
                            echo"<label>Réalisateur :</label>";
                            echo"<i class='producer' aria-hidden='true'></i>";
                            echo"<input class ='inputt' type='text' placeholder='Réalisateur' name='producer' value='' required='required'>";
                        echo"</div>";

                        echo"<div class='textbox1'>";
                            echo"<br>";
                            echo"<label>Date de sortie :</label>";
                            echo"<i class='date-sortie' aria-hidden='true'></i>";
                            echo"<input class ='inputt' type='date' placeholder='' name='date-sortie' min='1900-01-01' value=`date(Y-m-d)` required='required'>";
                        echo"</div>";
                    echo"</div>";
                    echo"<div class='dd'>";
                        echo"<div class='textbox1'>";
                            echo"<br>";
                            echo"<label>Nombre d'étoile :</label>";
                            echo"<i class='stars' aria-hidden='true'></i>";
                            echo"<input class ='inputt' type='number' placeholder='[0-5] :' name='stars' step='1' min='0' max='5' value='' required='required'>";
                        echo"</div>";

                        echo"<div class='textbox1'>";
                            echo"<br>";
                            echo"<label>Catégorie :</label>";
                            echo"<i class='cat' aria-hidden='true'></i>";
                            echo"<select name='categories[]' id='cat' multiple required='required' size='5'>
                                    <option value='' selected='selected' disabled>Choix categories</option>";
                            foreach ($category as $cat){
                                    echo "<option value={$cat[0]}>{$cat[1]}</option>";
                            }
                            echo"</select>";
                        echo"</div>";

                        echo"<div class='textbox1'>";
                            echo"<br>";
                            echo"<label>Prix :</label>";
                            echo"<i class='price' aria-hidden='true'></i>";
                            echo"<input class ='inputt' type='number' placeholder='Prix' name='price' step='0.01' min='0'  value='' required='required'>";
                        echo"</div>";

                        
                        echo"<div class='textbox1'>";
                            echo"<br>";
                            echo"<label for='file'>Fichier :</label>";
                            echo"<i class='artimg' aria-hidden='true'></i>";
                            echo"<input type='file' name='file' required='required' style='margin-bottom:20px;'>";
                        echo"</div>";
                        
                        
            
                        echo"<input class ='inputt' class='button' type='submit' name='add' value='Ajouter'>";
                    echo"</div>";
                echo"</div>";
            echo"</form>"; 
        echo"</div>";
        echo"<br>";
        echo"<hr>";
        echo"<br>";
        echo"<div class='admin-del-div'>";
            echo"<form action='' method='post'>";
                echo"<div class='del-film'>";
                        echo"<h1>Supprimer : </h1>";
                        echo"<div class='textbox1'>";
                            echo"<br>";
                            echo"<label>Titre :</label>";
                            echo"<i class='title' aria-hidden='true'></i>";
                            echo"<input class ='inputt' type='text' placeholder='Titre' name='title' value='' required='required'>";
                        echo"</div>";
                    echo"</div>";
                        echo"<input class ='inputt' class='button' type='submit' name='del' value='Supprimer'>";
                    echo"</div>";
                echo"</div>";
            echo"</form>"; 
        echo"</div>";
        ?>

        <?php
            if(ISSET($_POST['add'])){

                
                // var_dump($_FILES);
                // Getting the SQL injection-protected variables
                $title=$_POST['title'];
                $title = SQLite3::escapeString($title);
                $desc=$_POST['synopsis'];
                $desc = SQLite3::escapeString($desc);
                $rea=$_POST['producer'];
                $rea = SQLite3::escapeString($rea);
                $stars=$_POST['stars'];
                $stars = SQLite3::escapeString($stars);
                $catt=$_POST['categories'];
                $price=$_POST['price'];
                $price = SQLite3::escapeString($price);
                $date = $_POST['date-sortie'];

                // Finding the max FID and add 1 to create a new unique FID
                $max = $conn2->query("SELECT max(fid) as fid FROM info_film;");
                $listMax=$max->fetchArray(); 
                $fid = $listMax['fid'] + 1 ;

                $tmpName = $_FILES['file']['tmp_name'];

                move_uploaded_file($tmpName, "../img/film_img/{$fid}.jpg");

                // Creating a SQL request to add a new product to the DB
                $query = "INSERT INTO info_film (fid,description,note,realisateur,'date') VALUES ('$fid','$desc', '$stars', '$rea','$date');";
                $conn2->exec($query);
                $query = "INSERT INTO produits (fid,nom,prix,categorie1,categorie2) VALUES ('$fid','$title','$price','$catt[0]','$catt[1]');";
                $conn2->exec($query);
                echo "<div class='alert-success'>Ajout effectué avec succès</div>";
                
                // echo "<div class='alert-danger'>Ajout Impossible</div>";
            }

            if(ISSET($_POST['del'])){

                // Getting the SQL injection & XSS protected variables
                $title=htmlspecialchars($_POST['title']);
                $title = SQLite3::escapeString($title);

                $tables = array("produits","info_film","panier");

                $fidRq = $conn2->query("SELECT fid FROM produits WHERE nom = '{$title}'");
                $fid = $fidRq->fetchArray()['fid'];

                if(!$fid){
                    echo "<div class='alert-danger'>Sup Impossible : titre inexistant</div>";
                }else{
                    foreach($tables as $table){
                        $fidReq = $conn2->query("SELECT fid FROM {$table} WHERE fid={$fid}");
                        $fidList = $fidReq->fetchArray();                    
                        if($fidList){
                            $conn2->exec("DELETE FROM {$table} WHERE fid={$fid}");
                            
                        }
                        
                    }
                    echo "<div class='alert-success'>Suppression effectuée avec succès</div>";
                    
                }

            }
        ?>
    </div>
</div>
    <footer class="main_footer">
        <?php include("../templates/footer.php");?>
    </footer>
</body>
</html> 