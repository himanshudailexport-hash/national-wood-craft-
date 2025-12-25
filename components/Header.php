<?php
@session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta viewport="width=device-width, initial-scale=1.0">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ICONS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

    <!-- CUSTOM NAV CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <title></title>
</head>

<body>

    <header class="modern-header shadow-sm sticky-top">
        <nav class="navbar navbar-expand-lg container py-2">

            <a class="" href="index.php">
                <img src="assets/image/logo-nwc.png" alt="loading.." height="55px" width="auto">
                <!-- <img src="assets/image/logo-nwc.png" alt="loading.." height="70px"> -->
                <!-- <p >Natonal wood craf</p> -->
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <i class="fa-solid fa-bars fs-3"></i>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">

                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-lg-3 align-items-lg-center">
                    <li class="nav-item"><a class="nav-link modern-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link modern-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link modern-link" href="product.php">Products</a></li>
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link modern-link dropdown-toggle"
                            href="#"
                            id="productsDropdown"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Products
                        </a>

                        <ul class="dropdown-menu modern-dropdown" aria-labelledby="productsDropdown">
                            <li><a class="dropdown-item" href="product.php?category=wooden-furniture">Wooden Furniture</a></li>
                            <li><a class="dropdown-item" href="product.php?category=home-decor">Home Decor</a></li>
                            <li><a class="dropdown-item" href="product.php?category=wall-art">Wall Art</a></li>
                            <li><a class="dropdown-item" href="product.php?category=kitchen-items">Kitchen Items</a></li>
                            <li><a class="dropdown-item" href="product.php?category=custom-products">Custom Products</a></li>
                        </ul>
                    </li> -->

                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link modern-link dropdown-toggle"
                            href="#"
                            id="productsDropdown"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Products
                        </a>

                        <ul class="dropdown-menu modern-dropdown">
                            <li><a class="dropdown-item" href="#">Wooden Furniture</a></li>
                            <li><a class="dropdown-item" href="#">Home Decor</a></li>
                            <li><a class="dropdown-item" href="#">Wall Art</a></li>
                            <li><a class="dropdown-item" href="#">Kitchen Items</a></li>
                        </ul>
                    </li> -->


                    <li class="nav-item"><a class="nav-link modern-link" href="contact.php">Contact</a></li>
                </ul>

                <div class="d-flex ms-lg-4 gap-3 mt-3 mt-lg-0">

                    <!-- CART ICON -->
                    <a href="cart.php" class="icon-btn position-relative">
                        <i class="fa-solid fa-cart-shopping"></i>

                        <span id="cart-count"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="font-size: 10px; display:none;">
                        </span>
                    </a>

                    <!-- USER ICON -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="user/profile.php" class="icon-btn">
                            <i class="fa-solid fa-user"></i>
                        </a>
                    <?php else: ?>
                        <a href="user/login.php" class="icon-btn">
                            <i class="fa-solid fa-user"></i>
                        </a>
                    <?php endif; ?>

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
            let count = cart.reduce((sum, item) => sum + item.qty, 0);

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