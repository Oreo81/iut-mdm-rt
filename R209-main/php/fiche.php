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
        <div class="main_container_achat">
        <?php 
            $db = new SQLite3("../db/db.db");

            if(isset($_SESSION['uname'])) {
                $username = $_SESSION['uname'];
                $id_req = $db->query("SELECT compte.uid FROM compte WHERE username = '$username'");
                $id_user_session = $id_req->fetchArray()['uid'];
            }

            $movie = htmlspecialchars(trim($_GET['movie'])); #XSS protection
            $movie = SQLite3::escapeString($movie); #SQL-injection protection
            
            $fid_req = $db->query("SELECT fid FROM produits");
            $movie_info_req = $db->query("SELECT produits.*,info_film.* FROM produits,info_film WHERE produits.fid=$movie and produits.fid = info_film.fid");
            $com_req = $db->query("SELECT commentaire.* ,compte.username FROM commentaire,compte WHERE fid =$movie and commentaire.uid = compte.uid");
            
            $list_fid = [];
            while ($fid = $fid_req->fetchArray()) {
                $list_fid[] = $fid['fid'];
            }

            if(in_array($movie ,$list_fid)) {
                while ($movie_info = $movie_info_req->fetchArray()) {
                    echo "<div class='info_film'>";
                        echo "<div class='info_film_picture'>";
                            echo "<img src='../img/film_img/{$movie_info['fid']}.jpg' alt='-' width='350'> <br>";
                        echo "</div>";
                        echo "<div class='info_film_texte'>";
                            echo "<div class='info_film_titre'>";
                                echo "{$movie_info['nom']} | {$movie_info['date']} | {$movie_info['realisateur']}<br>";
                                echo"<img src='../img/{$movie_info['note']}star.png' alt='etoile' style='width:30%;height:auto;margin-left:15%;margin-right:15%;'>";
                            echo "</div>";
                            echo "<div class='info_film_desciption'>";
                                echo "<center>{$movie_info['description']}</center>";
                            echo "</div>";
                            echo "<div class='info_film_achat'>";
                                echo "{$movie_info['prix']}€";
                                if(isset($_SESSION['uname'])) {
                                    echo "<div class='achat'>";
                                        echo "<form method='post' action='' >";
                                            echo "<input class='button' type='submit' name='achat' value='Ajouter au panier' style='width: 100%;
                                            padding: 12px 20px; margin: 8px 0; display: inline-block; border: 1px solid #ccc; box-sizing: border-box; cursor:pointer'/>";
                                        echo "</form>";
                                    echo "</div>";

                                    $already_buy = $db->query("SELECT qte FROM panier WHERE uid = '$id_user_session' and fid = '$movie'"); 
                                    $nb_achat = $already_buy->fetchArray();
                                    if(is_bool($nb_achat)){
                                        if(ISSET($_POST['achat'])) {
                                            $query = "INSERT INTO panier ('uid','fid','qte') VALUES ('$id_user_session','$movie',1)";
                                            $db->exec($query);
                                            header("Refresh:0");
                                        }
                                    }
                                    elseif (is_array($nb_achat)){
                                        $nb =  $nb_achat['qte']+1;
                                        if(ISSET($_POST['achat'])) {
                                            $query = "UPDATE panier SET qte =$nb  WHERE ( uid=$id_user_session and fid=$movie)";
                                            $db->exec($query);
                                            header("Refresh:0");
                                        }
                                    }
                                }
                                else  {  
                                    echo "<div class='no_login'> <a href='login.php'>Connectez-vous</a> pour ajouter au panier ou <a href='register.php'>enregistrez-vous</a> </div>";
                                }   
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";

                    echo "<div class='section_commentaire'>";
                        $list_id_com = [];
                        while ($com = $com_req->fetchArray()) {
                            echo "<div class='commentaire'>";
                                echo "{$com['username']} ({$com['note']}/5): {$com['com']}";
                            echo "</div>";
                            $list_id_com[] = $com['username'];
                        }
                    echo "</div>";

                    echo "<div class='post_commentaire'>";
                    if(isset($_SESSION['uname'])) {  
                        if(in_array($_SESSION['uname'] ,$list_id_com)) {
                            echo "<div class='already_post'> Vous avez déjà poster un commentaire pour ce film";
                                echo "<form method='post'>";
                                    echo "<input class='button' type='submit' name='del_com' value='Le supprimer' style='width: 100%;
                                    padding: 12px 20px; margin: 8px 0; display: inline-block; border: 1px solid #ccc; box-sizing: border-box; cursor:pointer'/>";
                                echo " </form>";
                            echo "</div>";
                            if(isset($_POST['del_com'])){
                                $db->exec("DELETE FROM commentaire WHERE uid = $id_user_session and fid = $movie");
                                header("Refresh:0");
                            }
                        }

                        else {
                            echo '<h3 style="text-align: center; color: #fff; opacity: .75; margin: 8px;">Vous pouvez poster un commentaire sur le film sous le nom de '.$_SESSION['uname'].'</h3>';                        
                            echo "<form action='' method='post'>";
                                echo "<div class='textbox_com'>";
                                    // echo "<label>Commentaire :</label>";
                                    echo "<i class='commentaire_i' aria-hidden='true'></i>";
                                    echo "<div style='text-align: center;'>";
                                        echo "<input type='number' placeholder='Note: [0-5] :' name='note' id='note_select' step='1' min='0' max='5' value='' required='required' style='width:100%;'>";
                                        echo "<br>";
                                        echo "<textarea type='text' placeholder='max 500 caractère' name='commentaire' value='' required='required' maxlength='500'></textarea>";
                                        echo "<input class='button' type='submit' name='send_com' value='Envoyé le commentaire' style='width: 100%;
                                        padding: 12px 20px; display: inline-block; border: 1px solid #ccc; box-sizing: border-box; cursor:pointer'>";
                                    echo "</div>";
                                echo "</div>";

                            if(ISSET($_POST['send_com'])) {
                                $commentaire=htmlspecialchars($_POST['commentaire']); # XSS protection
                                $commentaire = SQLite3::escapeString($commentaire); # SQL-injection protection
                                $note=htmlspecialchars(trim($_POST['note'])); # XSS protection
                                $note = SQLite3::escapeString($note); # SQL-injection protection

                                $max = $db->query("SELECT max(cid) as cid FROM commentaire");
                                $listMax=$max->fetchArray(); 
                                $cid = $listMax['cid'] + 1 ;
                                
                                $query = "INSERT INTO commentaire ('cid','fid','note','com','uid') VALUES ('$cid','$movie', '$note', '$commentaire','$id_user_session')";
                                $db->exec($query);
                                header("Refresh:0");
                            }
                        }
                    } 
                    else  {  
                        echo "<div class='no_login'> <a href='login.php'>Connectez-vous</a> pour mettre un commentaire ou <a href='register.php'>enregistrez-vous</a> </div>";
                    }   
                    echo "</div>";
                } 
            }
            else{
                echo "<div class='alert-danger'> /!\ Le film demandé n'existe pas</div>";
            }
        ?>
       </div> 
    </div>  
    <footer class="main_footer">
        <?php include("../templates/footer.php");?>
    </footer>
</body>
</html>