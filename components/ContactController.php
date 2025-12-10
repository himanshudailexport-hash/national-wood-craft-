
<?php
session_start();
require "../config/db.php";

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




