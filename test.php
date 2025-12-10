<?php
@session_start();
include 'config/db.php';

// Fetch categories from DB
$catQuery = $con->prepare("SELECT id, name FROM categories ORDER BY name ASC");
$catQuery->execute();
$categories = $catQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta viewport="width=device-width, initial-scale=1.0" />

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ICONS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

    <!-- CUSTOM NAV CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<header class="modern-header shadow-sm sticky-top">
    <nav class="navbar navbar-expand-lg container py-2">

        <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
            <img src="assets/image/logo-nwc.png" alt="loading.." width="90px" height="90px">
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navMenu">
            <i class="fa-solid fa-bars fs-3"></i>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-lg-3 align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link modern-link" href="index.php">Home</a>
                </li>

                <!-- 🔥 CATEGORY DROPDOWN WITH ICON -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle  d-flex align-items-center"
                       href="#" id="categoryDropdown"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #fff;">
                        Categories 
                    </a>

                    <ul class="dropdown-menu">

                        <?php while ($row = $categories->fetch_assoc()) { ?>
                            <li>
                                <a class="dropdown-item"
                                   href="product.php?id=<?php echo $row['id']; ?>">
                                    <?php echo $row['name']; ?>
                                </a>
                            </li>
                        <?php } ?>

                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link modern-link" href="product.php">Products</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link modern-link" href="about.php">About</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link modern-link" href="contact.php">Contact</a>
                </li>
            </ul>

            <div class="d-flex ms-lg-4 gap-3 mt-3 mt-lg-0">

                <a href="cart.php" class="icon-btn position-relative">
                    <i class="fa-solid fa-cart-shopping"></i>

                    <span id="cart-count"
                          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                          style="font-size: 10px; display:none;">
                    </span>
                </a>

                <a href="user.php" class="icon-btn">
                    <i class="fa-solid fa-user"></i>
                </a>
            </div>

        </div>
    </nav>
</header>

<script>
    function loadCart() {
        return JSON.parse(localStorage.getItem("cartItems")) || [];
    }

    function updateCartBadge() {
        let cart = loadCart();
        let count = cart.reduce((sum, i) => sum + i.qty, 0);

        const badge = document.getElementById("cart-count");
        if (count > 0) {
            badge.style.display = "inline-block";
            badge.innerText = count;
        } else {
            badge.style.display = "none";
        }
    }

    updateCartBadge();
    window.addEventListener("storage", updateCartBadge);
    window.addEventListener("cart-updated", updateCartBadge);
</script>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
