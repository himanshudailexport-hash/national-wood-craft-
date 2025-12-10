<?php
session_start();
require "config/db.php";

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $_SESSION["contact_error"] = "All fields are required.";
        header("Location: contact.php");
        exit();
    }

    $stmt = $con->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        $_SESSION["contact_success"] = "Your message has been sent successfully!";
    } else {
        $_SESSION["contact_error"] = "Failed to send your message. Please try again.";
    }

    header("Location: contact.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>

    <link rel="apple-touch-icon" href="assets/image/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="assets/image/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .alert-position {
            max-width: 700px;
            margin: 20px auto;
        }
    </style>
</head>

<body>

    <?php include 'components/Header.php' ?>

    <!-- ALERT MESSAGES -->
    <div class="container alert-position">
        <?php if (isset($_SESSION["contact_success"])) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION["contact_success"]; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php unset($_SESSION["contact_success"]);
        } ?>

        <?php if (isset($_SESSION["contact_error"])) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION["contact_error"]; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php unset($_SESSION["contact_error"]);
        } ?>
    </div>

    <!-- Hero Section -->
    <section class="contact-hero py-5 text-center">
        <div class="container">
            <h1 class="fw-bold mb-3 text-wood-brown">Contact Us</h1>
            <p class="lead mx-auto" style="max-width: 700px;">
                Have questions about wooden handicrafts? Need custom work or business collaboration? We're here to help you.
            </p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-4">

                <!-- Contact Info -->
                <div class="col-md-5">
                    <div class="p-4 border rounded-4 shadow-sm bg-white h-100 contact-info">
                        <h4 class="mb-3">Contact Information</h4>

                        <p class="mb-3"><i class="fa-solid fa-location-dot me-2 text-wood-brown"></i><strong>Address:</strong> 11/1073-272, Aali Ki Chungi, Opp. Sabri Ka Bagh,
                            Saharanpur – 247001, (U.P.), India</p>

                        <p class="mb-3"><i class="fa-solid fa-envelope me-2 text-wood-brown"></i><strong>Email:</strong> info@nationalwoodcraft-ab.com</p>

                        <p class="mb-4"><i class="fa-solid fa-phone me-2 text-wood-brown"></i><strong>Phone:</strong> +91 8077661038 , 8077397148</p>

                        <hr>

                        <h6 class="fw-bold mb-2"><i class="fa-regular fa-clock me-2 text-wood-brown"></i>Business Hours</h6>
                        <p class="mb-1">Mon - Sat : 10AM - 8PM</p>
                        <p class="mb-0">Sunday : Closed</p>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-md-7">
                    <div class="p-4 border rounded-4 shadow-sm bg-white contact-form">
                        <h4 class="mb-4">Send us a Message</h4>

                        <form method="POST" action="contact.php">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-wood-brown"></i></span>
                                        <input type="text" name="name" class="form-control border-start-0" placeholder="Full Name" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-wood-brown"></i></span>
                                        <input type="email" name="email" class="form-control border-start-0" placeholder="Email Address" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-tag text-wood-brown"></i></span>
                                    <input type="text" name="subject" class="form-control border-start-0" placeholder="Subject" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-message text-wood-brown"></i></span>
                                    <textarea name="message" rows="5" class="form-control border-start-0" placeholder="Write your message..." required></textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-forest w-100 btn-lg fw-semibold">
                                <i class="fa-solid fa-paper-plane me-2"></i>Send Message
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Google Map -->
    <section class="pb-5">
        <div class="container">
            <div class="rounded-4 overflow-hidden shadow-sm">

                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m26!1m12!1m3!1d13825.0135665653!2d77.53063059926606!3d29.97214700770978!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m11!3e6!4m3!3m2!1d29.977221399999998!2d77.5325189!4m5!1s0x390eeb00c43a1857%3A0xa052912c9a4ab24!2sAali%20ki%20chungi%20gali%20no%2010%2C%203670%2C%20Purani%20Mandi%2C%20Saharanpur%2C%20Uttar%20Pradesh%20247001!3m2!1d29.9777584!2d77.53334699999999!5e0!3m2!1sen!2sin!4v1764159997361!5m2!1sen!2sin"
                    width="100%" height="360" style="border:0;" allowfullscreen loading="lazy"></iframe>
            </div>
        </div>
    </section>

    <?php include 'components/Footer.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>