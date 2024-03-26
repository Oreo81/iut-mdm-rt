<?php
// Connection script ~ create and use a table account in the DB

    // Connection to the DB file
    $conn = new SQLite3('../db/db.db');

    // Printing a disfunction of the DB message to the user
    if (empty($conn)){
        echo "<div class='alert-danger'>Unable to connect to the database: return the problem to the administrators</div>";
    }

    // Creating a SQL request to create a table in the DB
    $query = "CREATE TABLE IF NOT EXISTS compte (
        uid INTEGER PRIMARY KEY, 
        username TEXT NOT NULL, 
        password TEXT NOT NULL, 
        mail TEXT,
        adresse TEXT)";

    // Executing and getting the number of account in the DB
    $conn->exec($query);
    $query = $conn->query("SELECT COUNT(*) as count FROM compte");
    $Nb = $query->fetchArray();
    $countRow = $Nb['count'];

    // Testing if the table is new (line number == 0 ) --> creating an Admin account with 
    if($countRow == 0){
        $conn->exec("INSERT INTO compte (uid,username, password) VALUES(0,'admin','admin')");
    }
?>