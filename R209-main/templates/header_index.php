<?php
echo "<div class='main_header'>";
    echo "<div class='header_container'>";
        echo "<div class='header_title'>";
            echo "<div class='header_box_left'>";
                echo "<a href='./index.php'><img src='./img/icone.png' style='height:80px'><h1>Movie Planet</h1></a>";
            echo "</div>";
        echo "</div>";
        echo"<div class='header_center'>";
            echo"<form action='./php/recherche.php?recherche=' method='GET'>";
                echo "<div class='recherche_div'>";
                    echo"<input type='search' name='recherche' placeholder='Rechercher ...' style='font-size: 20px;'>";
                    echo"<button class='search_submit' type='submit' value='Rechercher'><img src='./img/search.png' alt='search'></button>";
                echo"</div>";
            echo"</form>";
        echo"</div>";
            echo "<div class='header_profile'>"; 
                echo "<div class='header_box_right'>";
                    if(isset($_SESSION['uname'])) {  
                        $username = $_SESSION['uname'];
                        $id_req = $db->query("SELECT compte.uid FROM compte WHERE username = '$username'");
                        $id_user_session = $id_req->fetchArray()['uid'];

                        $already_buy = $db->query("SELECT * FROM panier WHERE uid = '$id_user_session'"); 
                        $test = $db->query("SELECT * FROM panier WHERE uid = '$id_user_session'")->fetchArray();

                        $buy_items = 0;
                        
                        if(is_bool($test)){
                            echo "<h2>Welcome - {$_SESSION['uname']} - 0 Article |&ensp;</h2> <button><a href='./php/panier.php'><img src='./img/panier.png' alt='panier' style='width:30px%;height:30px'></a></button>";  
                        }
                        elseif (is_array($test)){
                            while ($buy = $already_buy->fetchArray()) {
                                $buy_items = $buy_items + $buy['qte'];
                            }
                            echo "<h2>Welcome - {$_SESSION['uname']} - {$buy_items} Articles |&ensp;</h2> <button><a href='./php/panier.php'><img src='./img/panier.png' alt='panier' style='width:30px%;height:30px'></a></button>";  
                        }
                    }
                    else  
                    {  
                        echo "<h2>No login |&ensp;</h2> <button><a href='./php/panier.php'><img src='./img/panier.png' alt='panier' style='width:30px%;height:30px'></a></button>";
                    }
                echo "</div>";    
        echo "</div>";
    echo "</div>";
    echo "<div class='header_down'>";
        echo "<div class='header_down_box_left'>";
            echo "<ul>";
                echo "<li>";
                    echo "<a href='./index.php'>Accueil</a> |";
                echo "</li>";
                echo "<li>";
                    echo "<a href='./php/all_categorie.php'>Catégories</a> |";
                echo "</li>";
                echo "<li>";
                    echo "<a href='./php/rech_av.php'>Recherche avancée</a>";
                echo "</li>";
            echo "</ul>";
        echo "</div>";
        echo "<div class='header_down_box_right'>";;
            echo "<div class='header_down_box_right_text'>";
                echo "<ul>";
                    echo "<li>";
                        if(isset($_SESSION['uname']))  
                            {  
                                $uname = $_SESSION['uname'];
                                if ($uname != 'admin') {
                                    echo "<a href='./php/logout.php'>Logout</a> |";
                                    echo "</li>";
                                    echo "<li>";
                                    echo "<a href='./php/panier.php'>Panier</a>";
                                }
                                else {
                                    echo "<a href='./php/logout.php'>Logout</a> |";
                                    echo "</li>";
                                    echo "<li>";
                                    echo "<a href='./php/page_admin.php'>Page admin</a> |";
                                    echo "</li>";
                                    echo "<li>";
                                    echo "<a href='./php/panier.php'>Panier</a>";
                                }
                            }
                            else  
                            {  
                                echo "<a href='./php/register.php'>Register</a> |";
                                echo "</li>";
                                echo "<li>";
                                echo "<a href='./php/login.php'>Login</a>";
                            }  
                        echo "</li>";
                echo "</ul>";
            echo "</div>";
        echo "</div>";
        
    echo "</div>";
        echo "<div class='header_down_void'>";
    echo "</div>";
echo "</div>";
?>

