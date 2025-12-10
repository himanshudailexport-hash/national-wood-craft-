<?php
session_start();
require_once('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $con->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashedPassword);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: ../index.php");
            exit;
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/img/favicon.ico" rel="icon">
    <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <title>User Login</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- FONT AWESOME -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">



    <!-- custom css -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <?php include '../components/Header.php'; ?>
    <div class="d-flex justify-content-center align-items-center vh-100 bg-light">

        <div class="card shadow p-4 m-4" style="width: 400px;">
            <h2 class="text-center mb-4 text-wood-brown">User Login</h2>
            <form method="post">
                <div class="mb-3">
                    <label for="InputEmail" class="form-label text-muted">Email address</label>
                    <input type="email" class="form-control" id="InputEmail" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="InputPassword" class="form-label text-muted">Password</label>
                    <input type="password" class="form-control" id="InputPassword" name="password" required>
                </div>
                <button name="submit" type="submit" class="btn-forest w-100 py-2">LOGIN</button>
                <div class="text-center mt-3">
                    <p>Don't have an account? <a href="signup.php" class="text-decoration-none">Signup here.</a> </p>
                </div>
            </form>
        </div>

    </div>

    <?php include '../components/Footer.php'; ?>
</body>

</html>