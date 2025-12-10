<?php
session_start();
include '../config/db.php';

// ------------------ FORM SUBMISSION HANDLING ------------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  $confirm_password = trim($_POST['confirm_password']);

  // Check: Password Match
  if ($password !== $confirm_password) {
    $message = "<div class='alert alert-danger text-center'>Passwords do not match!</div>";
  } else {
    // Check: Email Exists
    $check = $con->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    
    if ($check->num_rows > 0) {
      $message = "<div class='alert alert-danger text-center'>User already registered!</div>";
    } else {
      // Insert new user
      $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

      $insert = $con->prepare("
                INSERT INTO users (username, email, password, created_at)
                VALUES (?, ?, ?, NOW())
            ");
      $insert->bind_param("sss", $name, $email, $hashedPassword);

      if ($insert->execute()) {
        $message = "<div class='alert alert-success text-center'>Account created successfully! You can now login.</div>";
      } else {
        $message = "<div class='alert alert-danger text-center'>Something went wrong! Try again.</div>";
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
  <title>Sign Up | Wooden Handicrafts</title>

  <!-- bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <!-- FONT AWESOME -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">



  <!-- custom css -->
  <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
  <?php include '../components/Header.php'; ?>
  <div class="auth-body">
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
          <div class="auth-card p-4 p-md-5 rounded-4 shadow-lg bg-white">

            <h3 class="fw-bold text-center text-wood-brown mb-4">Create Account</h3>
            <p class="text-center text-muted mb-4">Join us and explore the beauty of handmade wooden art</p>

            <?php if (!empty($message)) echo $message; ?>

            <form method="POST">

              <div class="mb-3">
                <label class="form-label text-dark fw-semibold">Full Name</label>
                <input type="text" class="form-control form-control-lg" name="name" placeholder="Enter your name" required>
              </div>

              <div class="mb-3">
                <label class="form-label text-dark fw-semibold">Email</label>
                <input type="email" class="form-control form-control-lg" name="email" placeholder="Enter your email" required>
              </div>

              <div class="mb-3">
                <label class="form-label text-dark fw-semibold">Password</label>
                <input type="password" class="form-control form-control-lg" name="password" placeholder="Create a password" required>
              </div>

              <div class="mb-3">
                <label class="form-label text-dark fw-semibold">Confirm Password</label>
                <input type="password" class="form-control form-control-lg" name="confirm_password" placeholder="Confirm your password" required>
              </div>

              <button type="submit" class="px-2 py-2 rounded btn-sand w-100 btn-lg fw-semibold">
                <i class="fa-solid fa-user-plus me-2"></i>Create Account
              </button>

              <p class="text-center mt-4 mb-0">
                <small class="text-muted">Already have an account?
                  <a href="login.php" class="text-forest fw-semibold text-decoration-none">Login</a>
                </small>
              </p>

            </form>

          </div>
        </div>
      </div>
    </div>
  </div>


  <?php include '../components/Footer.php'; ?>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>