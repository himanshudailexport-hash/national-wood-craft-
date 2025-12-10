<?php
session_start();
session_unset();
session_destroy();
header("Location: ../index.php");
exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="../assets/img/favicon.ico" rel="icon">
    <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
</head>
<body>
    
</body>
</html>