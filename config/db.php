<?php
$host = "localhost";
$user = "root"; 
$pass = ""; 
$dbname = "nwc"; 

// $host = "localhost";
// $user = "u371702595_nwc"; 
// $pass = "Nationalwoodcraft@123nwc"; 
// $dbname = "u371702595_nwc"; 

$con = new mysqli($host, $user, $pass, $dbname);

if ($con->connect_error) {
    die(" Connection failed: " . $con->connect_error);
} 
?>
