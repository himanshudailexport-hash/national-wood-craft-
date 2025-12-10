<?php
session_start();


if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

include '../config/db.php';

$user_id = $_SESSION['user_id'];


$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($con, $query);
$user = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile | Wooden Handicrafts</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">

  <style>
    :root {
      --bg-light: #F7F3EB;
      --text-dark: #2B2926;
      --wood-brown: #7A5938;
      --sand: #C3A887;
      --forest: #3B5B4C;
    }

    .profile-body {
      background-color: var(--bg-light);
      min-height: 100vh;
    }

    .profile-card {
      max-width: 700px;
    }

    .profile-avatar {
      width: 100px;
      height: 100px;
      background-color: var(--forest);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .text-wood-brown {
      color: var(--wood-brown);
    }

    .btn-sand {
      background: var(--forest);
      color: #fff;
      border: none;
      border-radius: 10px;
      text-decoration: none;
    }

    .btn-sand:hover {
      background-color: var(--sand);
      color: #fff;
    }
  </style>
</head>

<body class="profile-body">

  <?php include '../components/Header.php'; ?>

  <section class="py-5">
    <div class="container">
      <div class="profile-card mx-auto shadow-sm bg-white p-4 p-md-5 rounded-4">

        <div class="text-center mb-4">
          <div class="profile-avatar mx-auto mb-3">
            <i class="fa-solid fa-user fa-3x text-white"></i>
          </div>

          <h4 class="fw-bold text-wood-brown mb-1">
            <?= $user['username']; ?>
          </h4>

          <p class="text-muted mb-0">
            <?= $user['email']; ?>
          </p>
        </div>

        <hr>

        <div class="row gy-3">

          <div class="col-md-6">
            <p class="mb-1 text-muted fw-semibold">Phone</p>
            <p class="text-dark"><?= $user['phone']; ?></p>
          </div>

          <div class="col-md-6">
            <p class="mb-1 text-muted fw-semibold">Address</p>
            <p class="text-dark"><?= $user['address']; ?></p>
          </div>

          <div class="col-md-6">
            <p class="mb-1 text-muted fw-semibold">City</p>
            <p class="text-dark"><?= $user['city']; ?></p>
          </div>

          <div class="col-md-6">
            <p class="mb-1 text-muted fw-semibold">State</p>
            <p class="text-dark"><?= $user['state']; ?></p>
          </div>

          <div class="col-md-6">
            <p class="mb-1 text-muted fw-semibold">Pincode</p>
            <p class="text-dark"><?= $user['pincode']; ?></p>
          </div>

          <div class="col-md-6">
            <p class="mb-1 text-muted fw-semibold">Country</p>
            <p class="text-dark"><?= $user['country']; ?></p>
          </div>

          <div class="col-md-6">
            <p class="mb-1 text-muted fw-semibold">Member Since</p>
            <p class="text-dark">
              <?= date("d M Y", strtotime($user['created_at'])); ?>
            </p>
          </div>

        </div>

        <div class="text-center mt-4">
          <a href="update-profile.php" class="btn-sand btn-lg py-2 px-2 fw-semibold me-3">
            <i class="fa-solid fa-user-pen me-2"></i>Edit Profile
          </a>

          <a href="logout.php" class="btn-sand px-2 py-2 fw-semibold">
            <i class="fa-solid fa-right-from-bracket me-2"></i>Logout
          </a>
        </div>

      </div>
    </div>
  </section>

  <?php include '../components/Footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>