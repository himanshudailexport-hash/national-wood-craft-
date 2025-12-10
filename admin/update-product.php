<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../config/db.php');

require "admin-auth.php"; 



$uploadDir = __DIR__ . "/../uploads/products/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Fetch categories and brands
$categories = [];
$brands = [];

$catResult = $con->query("SELECT id, name FROM categories ORDER BY name ASC");
while ($row = $catResult->fetch_assoc()) {
    $categories[] = $row;
}

$brandResult = $con->query("SELECT id, name FROM brands ORDER BY name ASC");
while ($row = $brandResult->fetch_assoc()) {
    $brands[] = $row;
}

$product = null;
$product_id = 0;

// Product fetch function
function fetchProduct($con, $product_id)
{
    $stmt = $con->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data;
}

// GET method => initial load
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $product = fetchProduct($con, $product_id);
    if (!$product) {
        echo "<div class='alert alert-danger'>Product not found!</div>";
        exit();
    }
} elseif ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo "<div class='alert alert-danger'>No product ID provided for update!</div>";
    exit();
}

// POST method => update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = intval($_POST['product_id'] ?? 0);

    // Re-fetch old product to get old image paths
    $product = fetchProduct($con, $product_id);
    if (!$product) {
        echo "<div class='alert alert-danger'>Invalid Product!</div>";
        exit();
    }

    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $discount_price = floatval($_POST['discount_price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);

    $category = intval($_POST['category'] ?? 0);
    $brand = intval($_POST['brand'] ?? 0);
    $rating = floatval($_POST['rating'] ?? 0);
    $tags = trim($_POST['tags'] ?? '');

    $isTrendingCategory = intval($_POST['isTrendingCategory'] ?? 0);
    $isBestSeller = intval($_POST['isBestSeller'] ?? 0);
    $isNewArrival = intval($_POST['isNewArrival'] ?? 0);
    $isLimitedStock = intval($_POST['isLimitedStock'] ?? 0);

    // Old images default
    $images = [
        $product['image1'] ?? null,
        $product['image2'] ?? null,
        $product['image3'] ?? null
    ];

    // New uploads overwrite old ones
    if (!empty($_FILES['images']['tmp_name'])) {
        foreach ($_FILES['images']['tmp_name'] as $i => $tmpName) {
            if ($i >= 3) break;
            if (is_uploaded_file($tmpName)) {
                $ext = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
                $newFileName = uniqid("prod_") . "." . $ext;
                $destination = $uploadDir . $newFileName;
                if (move_uploaded_file($tmpName, $destination)) {
                    $images[$i] = "uploads/products/" . $newFileName;
                }
            }
        }
    }

    $stmt = $con->prepare("
    UPDATE products 
    SET name = ?, description = ?, price = ?, discount_price = ?, stock = ?,
        category = ?,  brand = ?, rating = ?, 
        isTrendingCategory = ?, isBestSeller = ?, isNewArrival = ?, isLimitedStock = ?, 
        tags = ?, image1 = ?, image2 = ?, image3 = ?
    WHERE id = ?
");
    if (!$stmt) {
        die("❌ SQL Prepare failed: " . $con->error);
    }
    $stmt->bind_param(
        "ssddiiidiiiissssi",
        $name,
        $description,
        $price,
        $discount_price,
        $stock,

        $category,

        $brand,
        $rating,
        $isTrendingCategory,
        $isBestSeller,
        $isNewArrival,
        $isLimitedStock,
        $tags,
        $images[0],
        $images[1],
        $images[2],

        $product_id
    );

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'> Product updated successfully!</div>";
        $product = fetchProduct($con, $product_id);
    } else {
        echo "<div class='alert alert-danger'> Error updating product: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Update Product</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
  <!-- Custom CSS -->
  <link href="dashboard.css" rel="stylesheet" />
</head>

<body>

    <div class="container update-container mt-5 p-4">

        <h2 class="page-title mb-4">
            <i class="fa-solid fa-pen-to-square me-2"></i>
            Update Product (ID: <?= htmlspecialchars($product_id) ?>)
        </h2>

        <form action="" method="POST" enctype="multipart/form-data" class="product-form">

            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control styled-input"
                        value="<?= htmlspecialchars($product['name']) ?>" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select styled-input" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"
                                <?= ($product['category'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Brand</label>
                    <select name="brand" class="form-select styled-input" required>
                        <option value="">Select Brand</option>
                        <?php foreach ($brands as $brand): ?>
                            <option value="<?= $brand['id'] ?>"
                                <?= ($product['brand'] == $brand['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($brand['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" name="price" class="form-control styled-input"
                        value="<?= $product['price'] ?>" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Discount Price</label>
                    <input type="number" step="0.01" name="discount_price" class="form-control styled-input"
                        value="<?= $product['discount_price'] ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control styled-input"
                        value="<?= $product['stock'] ?>" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Rating</label>
                    <input type="number" step="0.1" min="0" max="5" name="rating"
                        class="form-control styled-input"
                        value="<?= $product['rating'] ?>">
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Tags</label>
                    <input type="text" name="tags" class="form-control styled-input"
                        value="<?= htmlspecialchars($product['tags']) ?>">
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3"
                        class="form-control styled-input"><?= htmlspecialchars($product['description']) ?></textarea>
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Upload Images (max 3):</label>
                    <input type="file" name="images[]" class="form-control styled-input" multiple>

                    <?php for ($i = 1; $i <= 3; $i++):
                        $imgCol = "image$i";
                        if (!empty($product[$imgCol])): ?>
                            <div class="current-image mt-2">
                                <span>Image <?= $i ?>:</span>
                                <img src="../<?= $product[$imgCol] ?>" width="100">
                            </div>
                    <?php endif;
                    endfor; ?>
                </div>

                <!-- Flags -->
                <?php
                $flags = [
                    "isTrendingCategory" => "Trending Category?",
                    "isBestSeller" => "Best Seller?",
                    "isNewArrival" => "New Arrival?",
                    "isLimitedStock" => "Limited Stock?"
                ];
                ?>

                <?php foreach ($flags as $key => $label): ?>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"><?= $label ?></label>
                        <select name="<?= $key ?>" class="form-select styled-input">
                            <option value="0" <?= ($product[$key] == 0) ? 'selected' : '' ?>>No</option>
                            <option value="1" <?= ($product[$key] == 1) ? 'selected' : '' ?>>Yes</option>
                        </select>
                    </div>
                <?php endforeach; ?>

                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-update">Update Product</button>
                </div>

            </div>
        </form>
    </div>

</body>

</html>