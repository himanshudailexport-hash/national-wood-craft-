<?php
session_start();
require_once('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$message = "";

// Fetch user details
$stmt = $con->prepare("SELECT username, email, phone, address, city, state, pincode, country FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Update user details without redirect
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $pincode = trim($_POST['pincode']);
    $country = trim($_POST['country']);

    $stmt = $con->prepare("UPDATE users 
        SET username=?, email=?, phone=?, address=?, city=?, state=?, pincode=?, country=? 
        WHERE id=?");

    $stmt->bind_param("ssssssssi", $username, $email, $phone, $address, $city, $state, $pincode, $country, $userId);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success text-center mb-3'>Profile updated successfully!</div>";

        // Refresh values immediately
        $user = [
            "username" => $username,
            "email" => $email,
            "phone" => $phone,
            "address" => $address,
            "city" => $city,
            "state" => $state,
            "pincode" => $pincode,
            "country" => $country
        ];
    } else {
        $message = "<div class='alert alert-danger text-center mb-3'>Something went wrong. Try again.</div>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile | Wooden Handicrafts</title>

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

        body.profile-body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
        }

        .profile-card {
            max-width: 750px;
            background-color: #fff;
            border-radius: 1rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
        }

        .text-wood-brown {
            color: var(--wood-brown) !important;
        }

        .btn-sand {
            background-color: var(--forest);
            color: #fff !important;
            border: none;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
        }

        .btn-sand:hover {
            background-color: var(--sand);
            color: #fff !important;
        }

        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }
    </style>
</head>

<body class="profile-body">

<?php include '../components/Header.php'; ?>

<section class="py-5">
    <div class="container">
        <div class="profile-card mx-auto p-4 p-md-5">

            <h3 class="fw-bold text-center text-wood-brown mb-4">Update Profile</h3>

            <?= $message ?>

            <form method="POST" action="">
                <div class="row gy-3">

                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">City</label>
                        <input type="text" name="city" value="<?= htmlspecialchars($user['city']) ?>" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">State</label>
                        <input type="text" name="state" value="<?= htmlspecialchars($user['state']) ?>" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Pincode</label>
                        <input type="text" name="pincode" value="<?= htmlspecialchars($user['pincode']) ?>" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" value="<?= htmlspecialchars($user['country']) ?>" class="form-control" required>
                    </div>

                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn-sand btn-lg px-4 fw-semibold">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Save Changes
                    </button>
                </div>

            </form>

        </div>
    </div>
</section>

<?php include '../components/Footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
