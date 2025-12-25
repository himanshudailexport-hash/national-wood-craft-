<?php
$host = "localhost";
$user = "root"; 
$pass = ""; 
$dbname = "nwc"; 

// $host = "localhost";
// $user = "u371702595_nwoods"; 
// $pass = "Nwoods@123"; 
// $dbname = "u371702595_nwoods"; 

$con = new mysqli($host, $user, $pass, $dbname);

if ($con->connect_error) {
    die(" Connection failed: " . $con->connect_error);
} 
?>
