<?php
session_start();
require "../config/db.php";

// If already logged in → go to dashboard
if (!empty($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

// If form submitted → process login
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (empty($_POST['email']) || empty($_POST['password'])) {
        $error = "Please enter email and password.";
    } else {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $stmt = $con->prepare("SELECT * FROM admin WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $error = "Admin not found";
        } else {
            $admin = $result->fetch_assoc();

            if (!password_verify($password, $admin['password'])) {
                $error = "Incorrect password";
            } else {
                // Login success
                $_SESSION["admin_id"] = $admin["id"];
                $_SESSION["admin_email"] = $admin["email"];

                header("Location: dashboard.php");
                exit();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
            background: #f8f9fa;
        }
    </style>
</head>

<body>

    <div class="container h-100">
        <div class="row justify-content-center align-items-center h-100"> 
            <div class="col-md-5">
                <div class="card shadow p-4">
                    <h3 class="text-center mb-4 fw-bold">Admin Login</h3>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button class="btn btn-primary w-100" type="submit">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
