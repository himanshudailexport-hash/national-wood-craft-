<?php
@session_start();
require_once __DIR__ . '/config/db.php';

// Get product ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
  die("Product ID is missing.");
}

$product_id = intval($_GET['id']);

// Fetch Product Details
$productQuery = $con->prepare("SELECT * FROM products WHERE id = ?");
$productQuery->bind_param("i", $product_id);
$productQuery->execute();
$productResult = $productQuery->get_result();

if ($productResult->num_rows === 0) {
  die("Product not found!");
}

$product = $productResult->fetch_assoc();

// Fetch Related Products (same category)
$relatedQuery = $con->prepare("SELECT * FROM products WHERE category = ? AND id != ? LIMIT 4");
$relatedQuery->bind_param("ii", $product['category'], $product_id);
$relatedQuery->execute();
$relatedProducts = $relatedQuery->get_result();



// Fetch Product Details + Category Name
$productQuery = $con->prepare("
    SELECT products.*, categories.name AS category_name 
    FROM products 
    LEFT JOIN categories ON products.category = categories.id
    WHERE products.id = ?
");
$productQuery->bind_param("i", $product_id);
$productQuery->execute();
$productResult = $productQuery->get_result();

if ($productResult->num_rows === 0) {
  die("Product not found!");
}

$product = $productResult->fetch_assoc();

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $product['name']; ?> | Wooden Handicrafts</title>
  <link rel="apple-touch-icon" href="assets/image/apple-touch-icon.png">
  <link rel="icon" type="image/png" href="assets/image/favicon.ico">
  <!-- BOOTSTRAP CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- FONT AWESOME -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

  <!-- CUSTOM CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

  <?php include 'components/Header.php'; ?>

  <section class="py-5 product-bg">
    <div class="container">
      <div class="row g-5 align-items-center">

        <!-- Product Images -->
        <div class="col-md-6">
          <div class="product-image-gallery text-center">

            <!-- Main Image -->
            <img src="<?php echo $product['image1']; ?>"
              class="img-fluid rounded-4 shadow-sm mb-3 main-image"
              alt="<?php echo $product['name']; ?>">
            <!-- Thumbnail Images -->
            <div class="d-flex justify-content-center gap-2">

              <?php if (!empty($product['image1'])) { ?>
                <img src="<?php echo $product['image1']; ?>" width="70" class="img-thumbnail gallery-thumb">
              <?php } ?>

              <?php if (!empty($product['image2'])) { ?>
                <img src="<?php echo $product['image2']; ?>" width="70" class="img-thumbnail gallery-thumb">
              <?php } ?>

              <?php if (!empty($product['image3'])) { ?>
                <img src="<?php echo $product['image3']; ?>" width="70" class="img-thumbnail gallery-thumb">
              <?php } ?>

            </div>
          </div>
        </div>

        <!-- Product Info -->
        <div class="col-md-6">

          <h2 class="fw-bold text-wood-brown mb-3">
            <?php echo $product['name']; ?>
          </h2>


          <h3 class="fw-bold text-forest mb-3">
            ₹<?php echo number_format($product['price']); ?>

            <?php if (!empty($product['discount_price'])) { ?>
              <small class="text-decoration-line-through text-muted fs-6">
                ₹<?php echo number_format($product['discount_price']); ?>
              </small>
            <?php } ?>
          </h3>
          <!-- Category -->
          <p class="mb-2">
            <strong>Category:</strong>
            <span class="text-muted">
              <?php echo $product['category_name'] ?? 'Uncategorized'; ?>
            </span>
          </p>

          <!-- Stock -->
          <p class="mb-3">
            <strong>Stock Available:</strong>
            <span class="text-<?php echo ($product['stock'] > 0) ? 'success' : 'danger'; ?>">
              <?php echo $product['stock'] > 0 ? $product['stock'] . ' pcs' : 'Out of Stock'; ?>
            </span>
          </p>



          <!-- Quantity -->
          <div class="d-flex align-items-center mb-4">
            <span class="me-3">Quantity:</span>
            <input id="productQty" type="number" class="form-control w-auto text-center" min="1" value="1">
          </div>

          <button class="btn btn-forest px-4 fw-semibold btn-add-cart">
            <i class="fa-solid fa-cart-plus me-2"></i>Add to Cart
          </button>
          <button class="btn btn-forest px-4 fw-semibold btn-buy-now">
            Buy Now
          </button>


          <div class="mt-4">
            <p><i class="fa-solid fa-check text-forest me-2"></i>Free shipping on orders over ₹999</p>
            <p><i class="fa-solid fa-rotate-left text-wood-brown me-2"></i>7-day easy returns</p>
          </div>

        </div>

      </div>
    </div>
  </section>

  <!-- Product Description -->
  <section class="py-5">
    <div class="container">
      <h4 class="fw-bold text-wood-brown mb-4">Product Description</h4>

      <p class="text-muted">
        <?php echo $product['description']; ?>
      </p>

      <?php if (!empty($product['tags'])) { ?>
        <strong>Tags:</strong>
        <p class="text-muted"><?php echo $product['tags']; ?></p>
      <?php } ?>
    </div>
  </section>

  <!-- Related Products -->
  <section class="py-5 bg-light">
    <div class="container">
      <h4 class="fw-bold text-forest mb-4 text-center">You May Also Like</h4>

      <div class="row g-4">

        <?php while ($rp = $relatedProducts->fetch_assoc()) { ?>
          <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
              <img src="<?php echo $rp['image1']; ?>" class="card-img-top rounded-top-4">

              <div class="card-body text-center">
                <h6 class="fw-semibold text-wood-brown mb-2">
                  <?php echo $rp['name']; ?>
                </h6>

                <p class="text-forest fw-bold mb-1">₹<?php echo number_format($rp['price']); ?></p>

                <a href="products-detail.php?id=<?php echo $rp['id']; ?>" class="btn btn-sm btn-outline-forest">
                  <i class="fa-solid fa-eye me-1"></i> View
                </a>
              </div>
            </div>
          </div>
        <?php } ?>

      </div>
    </div>
  </section>

  <?php include 'components/Footer.php'; ?>

  <script>
    const mainImage = document.querySelector('.main-image');
    const thumbs = document.querySelectorAll('.gallery-thumb');
    thumbs.forEach(thumb => {
      thumb.addEventListener('click', function() {
        mainImage.src = this.src;
      });
    });
  </script>

  <script>
    function loadCart() {
      return JSON.parse(localStorage.getItem("cartItems")) || [];
    }


    function saveCart(cart) {
      localStorage.setItem("cartItems", JSON.stringify(cart));


      window.dispatchEvent(new Event("storage"));
    }


    function updateCartBadge() {
      let cart = loadCart();
      let count = cart.reduce((sum, item) => sum + item.qty, 0);

      const badge = document.getElementById("cart-count");

      if (badge) {
        if (count > 0) {
          badge.style.display = "inline-block";
          badge.innerText = count;
        } else {
          badge.style.display = "none";
        }
      }
    }


    function addToCart(product) {
      let cart = loadCart();


      let existingItem = cart.find(item => item.id == product.id);

      if (existingItem) {
        existingItem.qty += product.qty;
      } else {
        cart.push(product);
      }

      saveCart(cart);
      updateCartBadge();

      alert("Product added to cart!");
    }




    document.querySelector(".btn-add-cart")?.addEventListener("click", function() {
      const qty = parseInt(document.querySelector("#productQty").value);

      const product = {
        id: "<?php echo $product['id']; ?>",
        name: "<?php echo $product['name']; ?>",
        price: <?php echo $product['price']; ?>,
        image: "<?php echo $product['image1']; ?>",
        qty: qty
      };

      addToCart(product);
    });


    updateCartBadge();
  </script>

  <script>
    document.querySelector(".btn-buy-now")?.addEventListener("click", function() {
      const qty = parseInt(document.querySelector("#productQty").value);

      const product = {
        id: "<?php echo $product['id']; ?>",
        name: "<?php echo $product['name']; ?>",
        price: <?php echo $product['price']; ?>,
        image: "<?php echo $product['image1']; ?>",
        qty: qty
      };


      let cart = loadCart();


      let existingItem = cart.find(item => item.id == product.id);

      if (existingItem) {
        existingItem.qty += qty;
      } else {
        cart.push(product);
      }


      saveCart(cart);
      updateCartBadge();


      window.location.href = "cart.php";
    });
  </script>


  <!-- BOOTSTRAP JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>