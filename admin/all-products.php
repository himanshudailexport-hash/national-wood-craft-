<?php
require "admin-auth.php"; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Products - List View</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/style.css">

  <style>
    :root {
      --bg-light: #F7F3EB;
      --text-dark: #2B2926;
      --wood-brown: #7A5938;
      --sand: #C3A887;
      --forest: #3B5B4C;
    }

    body {
      background-color: var(--bg-light);
      font-family: 'Poppins', sans-serif;
      color: var(--text-dark);
    }

    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .page-header h4 {
      color: var(--wood-brown);
      font-weight: 700;
    }

    .btn-sand {
      background-color: var(--sand);
      color: #fff;
      border: none;
      border-radius: 6px;
      transition: all 0.3s ease;
    }

    .btn-sand:hover {
      background-color: var(--forest);
      color: #fff;
    }

    .table-container {
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
      overflow: hidden;
    }

    .table thead {
      background-color: var(--sand);
      color: #fff;
    }

    .table thead th {
      font-weight: 600;
      border: none;
      padding: 1rem;
      vertical-align: middle;
    }

    .table tbody tr {
      transition: all 0.3s ease;
    }

    .table tbody tr:hover {
      background-color: rgba(195, 168, 135, 0.1);
      transform: scale(1.01);
    }

    .table td {
      vertical-align: middle;
      padding: 0.9rem 1rem;
    }

    .product-img {
      width: 55px;
      height: 55px;
      border-radius: 10px;
      object-fit: cover;
    }

    .product-name {
      font-weight: 600;
      color: var(--wood-brown);
    }

    .category-badge {
      background-color: var(--forest);
      color: #fff;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.8rem;
    }

    .product-price {
      color: var(--forest);
      font-weight: 600;
    }

    .action-btn {
      border: none;
      background: transparent;
      color: var(--sand);
      font-size: 1rem;
      transition: color 0.3s ease;
    }

    .action-btn:hover {
      color: var(--forest);
    }
  </style>
</head>

<body>

  <div class="container-fluid py-4">
    <div class="page-header">
      <h4 class="text-center"><i class="fa-solid fa-box me-2"></i>All Products</h4>
      <a href="add-product.php" class="btn btn-sand">
        <i class="fa-solid fa-plus me-2"></i>Add Product
      </a>
    </div>

    <div class="table-container p-3">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Product</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Date Added</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          require_once('../config/db.php');

          // Fetch products with category name & brand name (optional)
          $query = "
    SELECT p.*, 
           c.name AS category_name,
           b.name AS brand_name
    FROM products p
    LEFT JOIN categories c ON p.category = c.id
    LEFT JOIN brands b ON p.brand = b.id
    ORDER BY p.id DESC
";

          $result = mysqli_query($con, $query);

          $i = 1;

          while ($p = mysqli_fetch_assoc($result)) {

            // Product image fallback
            $img = (!empty($p["image1"]) && file_exists($p["image1"]))
              ? $p["image1"]
              : "assets/img/no-image.png";

            echo '
    <tr>
        <td>' . $i++ . '</td>

        <td>
            <div class="d-flex align-items-center">
                <img src="' . $img . '" class="product-img me-3" alt="' . $p["name"] . '">
                <div>
                    <span class="product-name">' . $p["name"] . '</span>
                </div>
            </div>
        </td>

        <td>
            <span class="category-badge">' . (!empty($p["category_name"]) ? $p["category_name"] : "N/A") . '</span>
        </td>

        <td class="product-price">₹' . $p["price"] . '</td>

        <td>' . $p["stock"] . '</td>

        <td>' . date("d M Y", strtotime($p["created_at"])) . '</td>

        <td class="text-center">
            <a href="update-product.php?id=' . $p["id"] . '" class="action-btn" title="Edit">
                <i class="fa-solid fa-pen"></i>
            </a>
            <a href="view-product.php?id=' . $p["id"] . '" class="action-btn" title="View">
                <i class="fa-solid fa-eye"></i>
            </a>
            <a href="delete-product.php?id=' . $p["id"] . '" class="action-btn" 
               onclick="return confirm(\'Delete this product?\')" title="Delete">
                <i class="fa-solid fa-trash"></i>
            </a>
        </td>
    </tr>';
          }
          ?>
        </tbody>

      </table>
    </div>
  </div>

</body>

</html>