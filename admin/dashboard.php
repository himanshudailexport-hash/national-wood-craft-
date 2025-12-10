<?php
include "../config/db.php";
require "admin-auth.php";   // protect this page
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

  <link href="dashboard.css" rel="stylesheet">
</head>

<body class="admin-panel">

  <!-- SIDEBAR -->
  <aside id="sidebar" class="sidebar">
    <div class="sidebar-header text-center py-3">
      <h4 class="text-white fw-bold mb-0">Admin Panel</h4>
      <p class="text-sand small mb-0">Wooden Handicrafts</p>
    </div>
    <hr class="text-sand opacity-25">

    <ul class="list-unstyled ps-3">
      <li><a href="dashboard.php" class="active"><i class="fa-solid fa-chart-line me-2"></i> Dashboard</a></li>

      <!-- PRODUCTS -->
      <li>
        <a href="#" class="toggle-submenu"><span><i class="fa-solid fa-box me-2"></i> Products</span> <i class="fa-solid fa-angle-down"></i></a>
        <ul class="submenu">
          <li><a href="add-product.php"><i class="fa-solid fa-plus me-2"></i> Add Product</a></li>
          <li><a href="all-products.php"><i class="fa-solid fa-list me-2"></i> Manage all Products</a></li>
        </ul>
      </li>

      <!-- CATEGORIES -->
      <li>
        <a href="#" class="toggle-submenu"><span><i class="fa-solid fa-layer-group me-2"></i> Categories</span> <i class="fa-solid fa-angle-down"></i></a>
        <ul class="submenu">
          <li><a href="manage-category.php"><i class="fa-solid fa-plus me-2"></i> Add Category</a></li>
          <li><a href="view-categories.php"><i class="fa-solid fa-list me-2"></i> View Categories</a></li>
        </ul>
      </li>

      <!-- ORDERS -->
      <li>
        <a href="#" class="toggle-submenu"><span><i class="fa-solid fa-cart-shopping me-2"></i> Orders</span> <i class="fa-solid fa-angle-down"></i></a>
        <ul class="submenu">
          <li><a href="all-orders.php"><i class="fa-solid fa-table me-2"></i> All Orders</a></li>
          <li><a href="pending-orders.php"><i class="fa-solid fa-clock me-2"></i> Pending Orders</a></li>
          <li><a href="completed-orders.php"><i class="fa-solid fa-check me-2"></i> Completed Orders</a></li>
        </ul>
      </li>



      <!-- Blogs -->
      <li>
        <a href="#" class="toggle-submenu"><span><i class="fa-solid fa-cart-shopping me-2"></i> Blog managements</span> <i class="fa-solid fa-angle-down"></i></a>
        <ul class="submenu">
          <li><a href="test.php"><i class="fa-solid fa-users me-2"></i> show all Blogs</a></li>
          <li><a href="add-blog.php"><i class="fa-solid fa-table me-2"></i>Create Blog</a></li>
        </ul>
      </li>

      <!-- Brands -->
      <li><a href="manage-brands.php"><i class="fa-solid fa-tags me-2"></i></i> Manage Brands</a></li>
      <!-- MESSAGES -->
      <li><a href="messages.php"><i class="fa-solid fa-envelope me-2"></i> Messages</a></li>

      <!-- LOGOUT -->
      <li><a href="admin-logout.php" class=""><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
    </ul>
  </aside>

  <!-- MAIN CONTENT -->
  <div id="page-content-wrapper">
    <!-- TOPBAR -->
    <nav class="navbar navbar-expand-lg shadow-sm py-3 px-4">
      <div class="container-fluid">
        <button class="btn btn-sand me-3 d-lg-none" id="menu-toggle"><i class="fa-solid fa-bars"></i></button>
        <h5 class="fw-bold mb-0 text-forest">Dashboard</h5>
        <div class="d-flex align-items-center ms-auto">
          <span class="me-3 fw-semibold text-dark">Hello, Admin</span>
          <img src="../assets/img/admin-avatar.png" class="rounded-circle" width="40" height="40" alt="">
        </div>
      </div>
    </nav>

    <!-- DASHBOARD CONTENT -->
    <section class="p-4">
      <div class="container-fluid">
        <div class="row g-4">
          <div class="col-md-3">
            <div class="card dashboard-card shadow-sm border-0 rounded-4">
              <div class="card-body text-center">
                <i class="fa-solid fa-box fa-2x mb-3 text-forest"></i>
                <h6 class="fw-bold ">Total Products</h6>
                <h4 class="fw-bold ">128</h4>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card dashboard-card shadow-sm border-0 rounded-4">
              <div class="card-body text-center">
                <i class="fa-solid fa-cart-shopping fa-2x mb-3 text-forest"></i>
                <h6 class="fw-bold">Total Orders</h6>
                <h4 class="fw-bold ">240</h4>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card dashboard-card shadow-sm border-0 rounded-4">
              <div class="card-body text-center">
                <i class="fa-solid fa-users fa-2x mb-3 text-forest"></i>
                <h6 class="fw-bold">Registered Users</h6>
                <h4 class="fw-bold ">76</h4>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card dashboard-card shadow-sm border-0 rounded-4">
              <div class="card-body text-center">
                <i class="fa-solid fa-sack-dollar fa-2x mb-3 text-forest"></i>
                <h6 class="fw-bold">Revenue</h6>
                <h4 class="fw-bold ">₹1.2L</h4>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-5">
          <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
              <h5 class="fw-bold text-wood-brown mb-3"><i class="fa-solid fa-chart-pie me-2 text-forest"></i> Overview</h5>
              <p class="text-muted">You can place your analytics, charts, and recent orders table here.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Sidebar toggle for mobile
    const sidebar = document.getElementById("sidebar");
    document.getElementById("menu-toggle").addEventListener("click", () => {
      sidebar.classList.toggle("active");
    });

    // Collapsible submenus
    document.querySelectorAll(".toggle-submenu").forEach(button => {
      button.addEventListener("click", e => {
        e.preventDefault();
        const submenu = button.nextElementSibling;
        submenu.style.display = submenu.style.display === "block" ? "none" : "block";
        button.querySelector("i.fa-angle-down").classList.toggle("rotate");
      });
    });
  </script>

  <style>
    /* arrow rotation animation */
    .rotate {
      transform: rotate(180deg);
      transition: transform 0.3s ease;
    }
  </style>
</body>

</html>
