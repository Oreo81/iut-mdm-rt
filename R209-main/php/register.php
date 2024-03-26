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
		echo "<div class='register'>";
			echo"<form action='' method='post'>";
				echo"<div class='register-box'>";
					echo"<h1>Register</h1>";
		
					echo"<div class='textbox'>";
						echo"<label>Nom d'utilisateur :</label>";
						echo"<i class='user' aria-hidden='true'></i>";
						echo"<input type='text' placeholder='Username' name='username' value='' required='required'>";
					echo"</div>";
					
					echo"<div class='textbox'>";
						echo"<label>Email :</label>";
						echo"<i class='user' aria-hidden='true'></i>";
						echo"<input type='email' placeholder='Email'name='email' value=''>";
					echo"</div>";
					
					echo"<div class='textbox'>";
						echo"<label>Adresse :</label>";
						echo"<i class='user' aria-hidden='true'></i>";
						echo"<input type='text' placeholder='Adresse' name='adresse' value=''>";
					echo"</div>";

					echo"<div class='textbox'>";
						echo"<label>Mot de passe :</label>";
						echo"<i class='lock' aria-hidden='true'></i>";
						echo"<input type='password' placeholder='Password' name='password' value='' required='required'>";
					echo"</div>";
		
					echo"<input class='button' type='submit' name='register' value='Sign In'>";
				echo"</div>";
			echo"</form>"; 
		echo"</div>";	
	echo"</div>";
?>

<?php
// Registering  script ~ use a html form to complete fields in a new DB line
	// Need a connection to be executed 
	require_once'connect.php';

	if(ISSET($_POST['register'])){
		// Getting the SQL injection and XSS protected variables
		$adresse=htmlspecialchars($_POST['adresse']); # XSS protection
		$adresse = SQLite3::escapeString($adresse); # SQL-injection protection
		$username=htmlspecialchars(trim($_POST['username'])); # XSS protection
		$username = SQLite3::escapeString($username); # SQL-injection protection
		$password=htmlspecialchars(trim($_POST['password'])); # XSS protection
		$password = SQLite3::escapeString($password); # SQL-injection protection
		$email=htmlspecialchars(trim($_POST['email'])); # XSS protection
		$email = SQLite3::escapeString($email); # SQL-injection protection

		// Finding the max UID and add 1 to create a new unique UID
		$max = $conn->query("SELECT max(uid) as uid FROM compte");
		$listMax=$max->fetchArray(); 
		$uid = $listMax['uid'] + 1 ;

		// Finding the username linked to $username 
 		$unameExists = $conn->query("SELECT * FROM compte WHERE username = '$username';");
 		$listUnameExists=$unameExists->fetchArray(); 

 		// Creating a SQL request to add a new account to the DB
 		$query = "INSERT INTO compte (uid,username,password,mail,adresse) VALUES ('$uid','$username', '$password', '$email','$adresse')";

 		if(ISSET($_POST['email'])){
   			$mail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

   			//Testing if the email is a validated one
		    if(filter_var($mail, FILTER_VALIDATE_EMAIL)){
		      // Testing if the $username already exists : if not -> executing the $query 
		 		if (!empty($listUnameExists)) {
		 			echo "<div class='alert-danger'>Register unsuccessful : Ce nom d'utilisateur est déjà renseigné</div>";
		 		}
				else {
					$conn->exec($query);
					header("Location: ./login.php");
					echo "<div class='alert-success'>Register successful</div>";
 				}
		    }
		    else {
		        echo"<div class='alert-danger'>Register unsuccessful : {$mail} n'est pas une adresse mail valide</div>";
			}
		}	
	}
?>
</div>
	<footer class="main_footer">
        <?php include("../templates/footer.php");?>
    </footer>
</body>
</html>