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
        include("../templates/header.php");?>
    </header>
    <div class="main_page">
        <?php
        session_start();
        session_destroy(); 
        echo "<div class='landr_div'>";
            echo "<div class='login'>";
                echo"<form action='' method='post'>";
                    echo"<div class='login-box'>";
                        echo"<h1>Login</h1>";
            
                        echo"<div class='textbox'>";
                            echo"<label>Nom d'utilisateur :</label>";
                            echo"<i class='user' aria-hidden='true'></i>";
                            echo"<input type='text' placeholder='Username' name='username' value='' required='required'>";
                        echo"</div>";
            
                        echo"<div class='textbox'>";
                            echo"<label>Mot de passe :</label>";
                            echo"<i class='lock' aria-hidden='true'></i>";
                            echo"<input type='password' placeholder='Password' name='password' value='' required='required'>";
                        echo"</div>";
            
                        echo"<input class='button' type='submit' name='login' value='Log In'>";
                    echo"</div>";
                echo"</form>";
            echo"</div>";
        echo"</div>";
        ?>

        <?php
        require_once 'connect.php';
        
        if(ISSET($_POST['login'])){
            $username = htmlspecialchars(trim($_POST['username']));
            $username = SQLite3::escapeString($username);
            $password = htmlspecialchars(trim($_POST['password']));
            $password = SQLite3::escapeString($password);

            $query=$conn->query("SELECT COUNT(*) as count FROM compte WHERE username = '$username' AND password ='$password'");
            $row=$query->fetchArray(); 
            $count=$row['count'];
    
            if($count > 0){
                session_start();
                $_SESSION['uname'] = $username;
                if ($username == 'admin'){
                    header("Location: page_admin.php");
                }
                elseif($username == 'munier'){
                        echo"<div class='alert-danger'>";
                            echo"<p><img src='../img/easter_egg.jpg' alt='easter_egg'</p>";
                        echo"</div>";
                            ?>
                            <style>
                                .landr_div {
                                    display: none;
                                }
                            </style>
                            <?php
                        echo "<div class='alert-danger'>Reidrection dans 2 secondes</div>";

                        header( "refresh:2;url=../index.php" );
                        


                }
                else{
                    header("Location: ../index.php");
                } 
            }else{
                echo "<div class='alert-danger'>Invalid username or password</div>";

            }
        }
        ?>
    </div>
    <footer class="main_footer">
        <?php include("../templates/footer.php");?>
    </footer>
</body>
</html>