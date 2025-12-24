<?php
session_start();
require_once "config/db.php";

// Get logged-in user ID
$user_id = isset($_SESSION['USER_ID']) ? $_SESSION['USER_ID'] : 0;

$success_message = "";

// Handle Order Submit
if ($_SERVER['REQUEST_METHOD'] === "POST") {

  $full_name      = $_POST['full_name'];
  $phone          = $_POST['phone'];
  $email          = $_POST['email'];
  $address        = $_POST['address'];
  $city           = $_POST['city'];
  $state          = $_POST['state'];
  $pincode        = $_POST['pincode'];
  $country        = $_POST['country'];
  $payment_method = $_POST['payment_method'];

  $total_amount     = $_POST['total_amount'];
  $product_ids_json = $_POST['product_ids'];

  $query = "INSERT INTO orders 
    (product_id, user_id, full_name, status_image, created_at, total_amount, 
     order_status, order_date, email, phone, address, city, state, pincode, country, 
     payment_method)
     VALUES 
     ('$product_ids_json','$user_id','$full_name','',NOW(),'$total_amount',
     'pending',NOW(),'$email','$phone','$address','$city','$state','$pincode','$country',
     '$payment_method')";

  if (mysqli_query($con, $query)) {
    $success_message = "Your order has been successfully placed!";
  } else {
    $success_message = "Order Failed. Please try again.";
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout | Wooden Handicrafts</title>
  <link rel="apple-touch-icon" href="assets/image/apple-touch-icon.png">
  <link rel="icon" type="image/png" href="assets/image/favicon.ico">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

  <?php include 'components/Header.php'; ?>

  <section class="py-5 bg-light-custom">
    <div class="container">

      <h2 class="fw-bold text-center text-wood-brown mb-5">
        <i class="fa-solid fa-bag-shopping"></i> Checkout
      </h2>

      <?php if ($success_message != "") { ?>
        <div class="alert alert-success text-center fw-bold">
          <?= $success_message ?>
        </div>

        <script>
          
          localStorage.removeItem("cartItems");

          //  Update cart badge immediately
          window.dispatchEvent(new Event("cart-updated"));

          
          const items = document.getElementById("order-items");
          if (items) items.innerHTML = "<p class='text-muted text-center'>Your cart is now empty</p>";

          document.getElementById("subtotal").innerText = "₹0";
          document.getElementById("total").innerText = "₹0";
        </script>
      <?php } ?>

      <div class="row g-4">

        <!-- Billing Form -->
        <div class="col-lg-7">
          <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">

              <h4 class="mb-3 text-dark">Billing Details</h4>

              <form id="checkout-form" method="POST">

                <input type="hidden" id="total_amount" name="total_amount">
                <input type="hidden" id="product_ids" name="product_ids">

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" required>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" required>
                  </div>

                  <div class="col-12 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                  </div>

                  <div class="col-12 mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="3" required></textarea>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" required>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label class="form-label">State</label>
                    <input type="text" name="state" class="form-control" required>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label class="form-label">Pincode</label>
                    <input type="text" name="pincode" class="form-control" required>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" required>
                  </div>

                  <div class="col-md-12 mb-3">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-control" required>
                      <option value="COD">Cash on Delivery</option>
                      <option value="Online">Online Payment</option>
                    </select>
                  </div>

                </div>

            </div>
          </div>
        </div>

        <!-- RIGHT SECTION -->
        <div class="col-lg-5">
          <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">

              <h4 class="mb-3 text-dark">Order Summary</h4>

              <div id="order-items"></div>

              <hr>

              <div class="d-flex justify-content-between mb-2">
                <strong>Subtotal</strong>
                <span id="subtotal">₹0</span>
              </div>

              <div class="d-flex justify-content-between mb-2">
                <strong>Shipping</strong>
                <span>₹99</span>
              </div>

              <div class="d-flex justify-content-between border-top pt-3 mb-3">
                <strong>Total</strong>
                <span id="total" class="fw-bold text-forest">₹0</span>
              </div>

              <button type="submit" form="checkout-form" class="btn btn-forest w-100 py-2 fw-semibold rounded-pill">
                Place Order
              </button>

              </form>

            </div>
          </div>
        </div>

      </div>

    </div>
  </section>

  <?php include 'components/Footer.php'; ?>

  <script>
    function loadCart() {
      return JSON.parse(localStorage.getItem("cartItems")) || [];
    }

    function renderCheckout() {
      let cart = loadCart();
      let container = document.getElementById("order-items");
      let subtotal = 0;
      let productIDs = [];

      if (cart.length === 0) {
        container.innerHTML = `<p class="text-muted text-center">Your cart is empty</p>`;
        return;
      }

      let html = "";

      cart.forEach(item => {
        let total = item.price * item.qty;
        subtotal += total;
        productIDs.push(item.id);

        html += `
        <div class="d-flex justify-content-between mb-3">
          <div>
            <h6 class="text-wood-brown mb-1">${item.name}</h6>
            <small class="text-muted">Qty: ${item.qty}</small>
          </div>
          <h6 class="text-forest fw-bold">₹${total}</h6>
        </div>`;
      });

      container.innerHTML = html;

      document.getElementById("subtotal").innerText = "₹" + subtotal;
      document.getElementById("total").innerText = "₹" + (subtotal + 99);

      document.getElementById("total_amount").value = subtotal + 99;
      document.getElementById("product_ids").value = JSON.stringify(productIDs);
    }

    renderCheckout();
  </script>

</body>

</html>