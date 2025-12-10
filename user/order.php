<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders | Wooden Handicrafts</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- FONT AWESOME -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">



    <!-- custom css -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="order-body">

  <?php include '../components/Header.php'; ?>

  <!-- PAGE TITLE -->
  <section class="order-header text-center py-5">
    <div class="container">
      <h1 class="fw-bold text-wood-brown mb-2"><i class="fa-solid fa-box-open me-2"></i>My Orders</h1>
      <p class="text-muted mb-0">Track your purchases and order details</p>
    </div>
  </section>

  <!-- ORDER HISTORY TABLE -->
  <section class="py-5">
    <div class="container">
      <div class="card shadow-sm border-0 rounded-4 p-4 order-card">
        <div class="table-responsive">
          <table class="table align-middle text-center">
            <thead class="table-light">
              <tr>
                <th>Order ID</th>
                <th>Product</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <!-- Example Row (Dynamic Data from DB in PHP) -->
              <tr>
                <td>#1001</td>
                <td>
                  <div class="d-flex align-items-center justify-content-center">
                    <img src="assets/img/sample-product.jpg" alt="" class="order-img me-3">
                    <span>Handcrafted Wooden Vase</span>
                  </div>
                </td>
                <td>10 Nov 2025</td>
                <td><span class="badge bg-success rounded-pill">Delivered</span></td>
                <td>₹2,499</td>
                <td>
                  <a href="order-details.php?id=1001" class="btn btn-sm btn-sand">
                    <i class="fa-solid fa-eye me-1"></i> View
                  </a>
                </td>
              </tr>

              <tr>
                <td>#1002</td>
                <td>
                  <div class="d-flex align-items-center justify-content-center">
                    <img src="assets/img/sample-product2.jpg" alt="" class="order-img me-3">
                    <span>Wooden Wall Clock</span>
                  </div>
                </td>
                <td>08 Nov 2025</td>
                <td><span class="badge bg-warning text-dark rounded-pill">Shipped</span></td>
                <td>₹1,799</td>
                <td>
                  <a href="order-details.php?id=1002" class="btn btn-sm btn-sand">
                    <i class="fa-solid fa-eye me-1"></i> View
                  </a>
                </td>
              </tr>

              <tr>
                <td>#1003</td>
                <td>
                  <div class="d-flex align-items-center justify-content-center">
                    <img src="assets/img/sample-product3.jpg" alt="" class="order-img me-3">
                    <span>Wooden Sculpture Set</span>
                  </div>
                </td>
                <td>05 Nov 2025</td>
                <td><span class="badge bg-secondary rounded-pill">Pending</span></td>
                <td>₹3,999</td>
                <td>
                  <a href="order-details.php?id=1003" class="btn btn-sm btn-sand">
                    <i class="fa-solid fa-eye me-1"></i> View
                  </a>
                </td>
              </tr>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  <?php include '../components/Footer.php'; ?>

  <!-- BOOTSTRAP JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
