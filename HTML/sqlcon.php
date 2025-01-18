<?php 


// Database connection details ////////////////////////////////////////////////
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'gge');

$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($con -> connect_error) {
    die("Failed to connect to database! " . $con -> connect_error);
  }

?>