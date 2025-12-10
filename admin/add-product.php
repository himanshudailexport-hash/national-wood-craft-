<?php

require "admin-auth.php"; 


require_once('../config/db.php');

// Fetch categories & brands
$categories = [];
$brands = [];

$catResult = $con->query("SELECT id, name FROM categories ORDER BY name ASC");
while ($row = $catResult->fetch_assoc()) $categories[] = $row;

$brandResult = $con->query("SELECT id, name FROM brands ORDER BY name ASC");
while ($row = $brandResult->fetch_assoc()) $brands[] = $row;

// Message placeholder
$message = "";

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $con->real_escape_string($_POST['product_name'] ?? '');
    $category = intval($_POST['category'] ?? 0);
    $brand = intval($_POST['brand'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $description = $con->real_escape_string($_POST['description'] ?? '');
    $tags = $con->real_escape_string($_POST['tags'] ?? '');
    $rating = floatval($_POST['rating'] ?? 0);
    $discount_price = floatval($_POST['discount_price'] ?? 0);

    $isTrendingCategory = intval($_POST['isTrendingCategory'] ?? 0);
    $isBestSeller = intval($_POST['isBestSeller'] ?? 0);
    $isNewArrival = intval($_POST['isNewArrival'] ?? 0);
    $isLimitedStock = intval($_POST['isLimitedStock'] ?? 0);


    // Handle up to 3 images
    $uploadDir = "../uploads/products/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $imagePaths = [null, null, null];

    if (!empty($_FILES['images']['tmp_name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $i => $tmpName) {
            if ($i >= 3) break;
            if (is_uploaded_file($tmpName)) {
                $ext = pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION);
                $filename = uniqid("prod_", true) . "." . strtolower($ext);
                $targetPath = $uploadDir . $filename;
                if (move_uploaded_file($tmpName, $targetPath)) {
                    $imagePaths[$i] = "uploads/products/" . $filename;
                }
            }
        }
    }

    // Insert query
    $stmt = $con->prepare("INSERT INTO products 
    (name, description, price, discount_price, stock, category, brand, rating, 
    isTrendingCategory, isBestSeller, isNewArrival, isLimitedStock, tags, image1, image2, image3) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param(
            "ssddiiidiiiissss",
            $name, $description, $price, $discount_price, $stock, 
            $category, $brand, $rating, $isTrendingCategory, $isBestSeller,
            $isNewArrival, $isLimitedStock, $tags, $imagePaths[0], $imagePaths[1], $imagePaths[2]
        );

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success mt-3'> Product added successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger mt-3'> Error: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        $message = "<div class='alert alert-danger mt-3'> SQL Prepare failed: " . $con->error . "</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Product | Admin Panel</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
  <!-- Custom CSS -->
  <link href="dashboard.css" rel="stylesheet" />
</head>

<body class="admin-panel">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg shadow-sm py-3 px-4 bg-white">
    <div class="container-fluid">
      <a href="dashboard.php" class="btn btn-sand me-3"><i class="fa-solid fa-arrow-left"></i> Back</a>
      <h5 class="fw-bold mb-0 text-wood-brown">Add New Product</h5>
      <div class="d-flex align-items-center ms-auto">
        <span class="fw-semibold text-dark me-3">Hello, Admin</span>
        <img src="../assets/img/admin-avatar.png" class="rounded-circle" width="40" height="40" alt="" />
      </div>
    </div>
  </nav>

  <!-- Main -->
  <section class="container py-5">
    <div class="card shadow-sm border-0 rounded-4 p-4">
      <h4 class="fw-bold text-wood-brown mb-4"><i class="fa-solid fa-box me-2"></i> Product Details</h4>

      <?= $message ?>

      <form method="POST" enctype="multipart/form-data">
        <div class="row g-4">
          <!-- Product Name -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Product Name</label>
            <input type="text" class="form-control" name="product_name" placeholder="Enter product name" required />
          </div>

          <!-- Category -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Select Category</label>
            <select name="category" class="form-select" required>
              <option value="">Choose Category</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Brand -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Brand</label>
            <select name="brand" class="form-select">
              <option value="">Choose Brand</option>
              <?php foreach ($brands as $brand): ?>
                <option value="<?= $brand['id'] ?>"><?= htmlspecialchars($brand['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Price -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Price (₹)</label>
            <input type="number" class="form-control" name="price" placeholder="Enter price" required />
          </div>

          <!-- Discount -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Discount Price</label>
            <input type="number" class="form-control" name="discount_price" placeholder="Enter discount price" />
          </div>

          <!-- Stock -->
          <div class="col-md-6">
            <label class="form-label fw-semibold">Stock Quantity</label>
            <input type="number" class="form-control" name="stock" placeholder="Available quantity" required />
          </div>

          <!-- Description -->
          <div class="col-md-12">
            <label class="form-label fw-semibold">Description</label>
            <textarea class="form-control" name="description" rows="4" placeholder="Enter product description..."></textarea>
          </div>

          <!-- Tags -->
          <div class="col-md-12">
            <label class="form-label fw-semibold">Tags (comma-separated)</label>
            <input type="text" class="form-control" name="tags" placeholder="e.g., wooden, handmade, eco-friendly" />
          </div>

          <!-- Images -->
          <div class="col-md-12">
            <label class="form-label fw-semibold">Product Images (max 3)</label>
            <input type="file" class="form-control" name="images[]" accept="image/*" multiple required />
          </div>

          <!-- Special Flags -->
          <div class="col-md-3">
            <label class="form-label fw-semibold">Is Trending?</label>
            <select class="form-select" name="isTrendingCategory">
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label fw-semibold">Best Seller?</label>
            <select class="form-select" name="isBestSeller">
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label fw-semibold">New Arrival?</label>
            <select class="form-select" name="isNewArrival">
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label fw-semibold">Limited Stock?</label>
            <select class="form-select" name="isLimitedStock">
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
          </div>

          <div class="col-12 text-end mt-3">
            <button type="reset" class="btn btn-outline-secondary me-2">Clear</button>
            <button type="submit" class="btn btn-sand">
              <i class="fa-solid fa-plus"></i> Add Product
            </button>
          </div>
        </div>
      </form>
    </div>
  </section>
</body>
</html>
