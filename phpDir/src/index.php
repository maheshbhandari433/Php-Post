<?php

// Check if the request is for the default page
if ($_SERVER['REQUEST_URI'] === '/') {
    // Redirect to home.php
    header('Location: home.php');
    exit;
}
// The MySQL service named in the docker-compose.yml.
$host = 'db2';

// Database user name
$dbname = 'db';

// Database user name
$user = 'root';

//database user password
$pass = 'lionPass';

// check the MySQL connection status
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
   /*  echo "Welcome to POST ";
    echo '<br>';
   echo('You are signed in as '.$user);  */
}

/* $conn->close(); closes db connection*/
?>