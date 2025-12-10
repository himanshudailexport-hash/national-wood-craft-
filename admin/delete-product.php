<?php
include('../config/db.php');

require "admin-auth.php"; 


if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $delete_sql = "DELETE FROM products WHERE id = ?";
    $delete_stmt = $con->prepare($delete_sql);
    $delete_stmt->bind_param("i", $product_id);

    if ($delete_stmt->execute()) {
        header("Location: /nwc/admin/all-products.php");
        exit();
    } else {
        echo "Error deleting product: " . $con->error;
    }
} else {
    echo "Product ID is missing.";
    exit();
}
?>
