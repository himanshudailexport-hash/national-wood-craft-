<?php
include 'config/db.php';


$category_id = isset($_GET['category']) ? intval($_GET['category']) : 0;


$categoryQuery = $con->query("SELECT id, name FROM categories ORDER BY name ASC");
$categories = $categoryQuery->fetch_all(MYSQLI_ASSOC);

// Fetch products
if ($category_id > 0) {
    $sql = "SELECT p.*, c.name AS category_name 
            FROM products p
            LEFT JOIN categories c ON p.category = c.id
            WHERE p.category = $category_id
            ORDER BY p.id DESC";
} else {
    $sql = "SELECT p.*, c.name AS category_name 
            FROM products p
            LEFT JOIN categories c ON p.category = c.id
            ORDER BY p.id DESC";
}

$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product List - Wooden Handicrafts</title>

    <link rel="apple-touch-icon" href="assets/image/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="assets/image/favicon.ico">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <?php include 'components/Header.php' ?>

    <!-- CATEGORY FILTER BUTTONS -->
    <section class="category-section py-5 text-center">
        <div class="container">
            <h1 class="page-title mb-4 text-wood-brown fw-bold">Our Products</h1>

            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="product.php" class="category-btn <?= $category_id == 0 ? 'active' : '' ?>" style="text-decoration: none;">All</a>

                <?php foreach ($categories as $cat) { ?>
                    <a href="product.php?category=<?= $cat['id'] ?>"
                        class="category-btn <?= ($category_id == $cat['id']) ? 'active' : '' ?>" style="text-decoration: none;">
                        <?= ucfirst($cat['name']); ?>
                    </a>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- PRODUCT LIST -->
    <section class="product-section py-4">
        <div class="container">
            <div class="row g-4 justify-content-center">

                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {

                        $name = htmlspecialchars($row['name']);
                        $price = htmlspecialchars($row['price']);
                        $image = htmlspecialchars($row['image1']);
                        $category_name = htmlspecialchars($row['category_name']);
                ?>
                        <div class="col-md-3 col-sm-6 product-card">
                            <div class="card border-0 shadow-sm product-box h-100">
                                <img src="<?= $image ?>" class="card-img-top rounded-top-4" alt="<?= $name ?>">

                                <div class="product-info text-center p-3">
                                    <h3 class="fw-semibold text-wood-brown fs-6"><?= $name ?></h3>
                                    <p class="price fw-bold text-forest mb-1">₹<?= $price ?></p>
                                    <p class="text-muted small mb-3"><?= $category_name ?></p>

                                    <div class="d-flex gap-3">
                                        <button
                                            class="btn btn-add w-100 add-to-cart"
                                            data-id="<?= $row['id'] ?>"
                                            data-name="<?= $name ?>"
                                            data-price="<?= $price ?>"
                                            data-image="<?= $image ?>"
                                            data-category="<?= $category_name ?>">
                                            <i class="fa-solid fa-cart-plus me-2"></i> Add
                                        </button>

                                        <a href="products-detail.php?id=<?= $row['id'] ?>" class="btn btn-add-forest w-100">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                <?php
                    }
                } else {
                    echo "<p class='text-center text-muted'>No products found.</p>";
                }
                ?>

            </div>
        </div>
    </section>

    <?php include 'components/Footer.php' ?>

    <!-- ADD TO CART SCRIPT -->
    <script>
        function loadCart() {
            return JSON.parse(localStorage.getItem("cartItems")) || [];
        }

        function saveCart(cart) {
            localStorage.setItem("cartItems", JSON.stringify(cart));
            window.dispatchEvent(new Event("cart-updated"));
        }

        // Add to cart
        document.querySelectorAll(".add-to-cart").forEach(btn => {
            btn.addEventListener("click", () => {

                let cart = loadCart();

                let product = {
                    id: btn.dataset.id,
                    name: btn.dataset.name,
                    price: parseFloat(btn.dataset.price),
                    image: btn.dataset.image,
                    category: btn.dataset.category,
                    qty: 1
                };

                let existing = cart.find(item => item.id === product.id);

                if (existing) {
                    existing.qty += 1;
                } else {
                    cart.push(product);
                }

                saveCart(cart);
                alert("Added to cart!");
            });
        });
    </script>

</body>
</html>
