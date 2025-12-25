<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopping Cart</title>
  <link rel="apple-touch-icon" href="assets/image/apple-touch-icon.png">
  <link rel="icon" type="image/png" href="assets/image/favicon.ico">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

  <?php include 'components/Header.php' ?>

  <section class="py-5">
    <div class="container">

      <h2 class="fw-bold mb-4 text-wood-brown text-center">Your Shopping Cart</h2>

      <div class="row g-4">

        <!-- LEFT: CART ITEMS -->
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

              <div id="cart-items"></div>

              <div class="text-start mt-4">
                <a href="product.php" class="btn btn-forest px-4 fw-semibold">
                  ← Continue Shopping
                </a>
              </div>

            </div>
          </div>
        </div>

        <!-- RIGHT: SUMMARY -->
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

              <h5 class="fw-bold mb-4 text-forest">Order Summary</h5>

              <div class="d-flex justify-content-between mb-2">
                <span class="text-dark">Subtotal</span>
                <span id="subtotal" class="fw-semibold">₹0</span>
              </div>

              <div class="d-flex justify-content-between mb-2">
                <span class="text-dark">Shipping</span>
                <span class="fw-semibold">₹99</span>
              </div>

              <div class="d-flex justify-content-between border-top pt-3">
                <span class="fw-bold text-dark">Total</span>
                <span id="total" class="fw-bold text-forest">₹0</span>
              </div>

              <button class="btn btn-forest w-100 mt-4 py-2 fw-semibold rounded-pill"
                onclick="window.location.href='checkout.php'">
                Proceed to Checkout
              </button>

            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <script>
    function loadCart() {
      return JSON.parse(localStorage.getItem("cartItems")) || [];
    }

    function saveCart(cart) {
      localStorage.setItem("cartItems", JSON.stringify(cart));

      // 🔥 Notify header to update badge instantly
      window.dispatchEvent(new Event("cart-updated"));
    }

    function renderCart() {
      let cart = loadCart();
      let container = document.getElementById("cart-items");

      if (cart.length === 0) {
        container.innerHTML = `<p class="text-center text-muted">Your cart is empty</p>`;
        document.getElementById("subtotal").innerText = "₹0";
        document.getElementById("total").innerText = "₹0";
        return;
      }

      let html = "";
      let subtotal = 0;

      cart.forEach((item, index) => {
        let total = item.price * item.qty;
        subtotal += total;

        html += `
        <div class="cart-row mb-4 pb-4 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-center">
            
            <div class="d-flex align-items-center mb-3 mb-md-0">
                <img src="${item.image}" class="cart-img rounded-3 me-3">
                <div>
                    <h5 class="fw-semibold mb-1 text-wood-brown">${item.name}</h5>
                    
                </div>
            </div>

            <div class="d-flex align-items-center">
                <button class="qty-btn" onclick="changeQty(${index}, -1)">−</button>
                <span class="qty-display">${item.qty}</span>
                <button class="qty-btn" onclick="changeQty(${index}, 1)">+</button>

                <h6 class="fw-bold mb-0 ms-3 text-forest">₹${total}</h6>

                <button class="btn btn-link text-danger ms-3 p-0" onclick="removeItem(${index})">
                    Remove
                </button>
            </div>
        </div>
        `;
      });

      container.innerHTML = html;

      document.getElementById("subtotal").innerText = "₹" + subtotal;
      document.getElementById("total").innerText = "₹" + (subtotal + 99);
    }

    function changeQty(index, step) {
      let cart = loadCart();
      cart[index].qty += step;
      if (cart[index].qty < 1) cart[index].qty = 1;

      saveCart(cart);
      renderCart();
    }

    function removeItem(index) {
      let cart = loadCart();
      cart.splice(index, 1);
      saveCart(cart);
      renderCart();
    }

    renderCart();
  </script>

  <?php include 'components/Footer.php' ?>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


  <script>
    window.addEventListener("cart-updated", function() {
      if (typeof updateCartBadge === "function") {
        updateCartBadge();
      }
    });

    document.addEventListener("DOMContentLoaded", function() {
      if (typeof updateCartBadge === "function") {
        updateCartBadge();
      }
    });
  </script>

</body>

</html>