<?php
@session_start();
require_once __DIR__ . '/../config/db.php'; // DB connection

// If admin already logged in → redirect to dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

$success = "";
$error = "";

// When form is submitted
if (isset($_POST['signup'])) {

    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Check if email already exists
    $check = mysqli_query($con, "SELECT * FROM admin WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Email already registered!";
    } else {

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert admin record
        $insert = mysqli_query($con, "
            INSERT INTO admin (email, password, date)
            VALUES ('$email', '$hashedPassword', NOW())
        ");

        if ($insert) {
            $success = "Admin registered successfully! You can now login.";
        } else {
            $error = "Something went wrong!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f6f7fb;
        }

        .signup-container {
            max-width: 420px;
            margin: 80px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <div class="signup-container">
        <h3 class="text-center mb-4">Admin Signup</h3>

        <?php if ($error != "") { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <?php if ($success != "") { ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>

        <form method="POST">

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" required class="form-control" placeholder="Enter email">
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" required class="form-control" placeholder="Enter password">
            </div>

            <button type="submit" name="signup" class="btn btn-primary w-100">Signup</button>

            <div class="text-center mt-3">
                <a href="admin-login.php">Already have an account? Login</a>
            </div>
        </form>
    </div>

</body>

</html>
