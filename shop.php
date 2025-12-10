<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- BOOTSTRAP CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- you must keep this meta also for responsiveness -->
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<!-- custom css -->
<link rel="stylesheet" href="assets/css/style.css">

</head>
<body>
    <?php



$products = [
  [
    'id' => 101,
    'title' => 'Handcarved Mango Wood Bowl',
    'subtitle' => 'Natural finish • 10 inch',
    'price' => '2499',
    'image' => 'assets/image/Picture3.jpg',
    'rating' => 4.6
  ],
  [
    'id' => 102,
    'title' => 'Walnut Wall Shelf',
    'subtitle' => 'Walnut finish • 2-tier',
    'price' => '4999',
    'image' => 'assets/image/Picture2.jpg',
    'rating' => 4.8
  ],
  
];

// ---------- OPTION B: Real DB (uncomment + adapt) ----------
/*
try {
  $pdo = new PDO("mysql:host=YOUR_HOST;dbname=YOUR_DB;charset=utf8mb4", "DB_USER", "DB_PASS", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ]);
  $stmt = $pdo->query("SELECT id, title, subtitle, price, image, rating FROM products WHERE is_active = 1 ORDER BY created_at DESC LIMIT 48");
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  // log error and fallback to demo data or show friendly message
  error_log($e->getMessage());
}
*/
?>



<!-- Page content -->
<main class="container">
  <!-- Hero / Title row -->
  <section class="shop-hero">
    <div class="row align-items-center">
      <div class="col-md-7">
        <h1 class="display-6 fw-bold" style="color:var(--text-dark)">Shop Handcrafted Wooden Decor</h1>
        <p class="lead text-muted">Explore our curated selection of mango & walnut wood pieces — handcrafted by skilled artisans.</p>
      </div>
      <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <a href="categories.php" class="btn btn-outline-secondary me-2">Browse Categories</a>
        <a href="contact.php" class="btn btn-forest-outline">Wholesale Inquiry</a>
      </div>
    </div>
  </section>

  <!-- Controls: search + sort + view -->
  <section class="shop-controls border-top border-bottom py-3 mb-4">
    <div class="row gx-2 align-items-center">
      <div class="col-md-4">
        <form class="d-flex" role="search" method="GET" action="shop.php">
          <input name="q" class="form-control me-2" type="search" placeholder="Search products, e.g. bowl, shelf" value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>" aria-label="Search">
          <button class="btn btn-forest-outline" type="submit">Search</button>
        </form>
      </div>

      <div class="col-md-5 text-center text-md-start mt-2 mt-md-0">
        <div class="btn-group" role="group" aria-label="filters">
          <a href="?sort=new" class="btn btn-sm btn-outline-secondary">Newest</a>
          <a href="?sort=popular" class="btn btn-sm btn-outline-secondary">Popular</a>
          <a href="?sort=price_asc" class="btn btn-sm btn-outline-secondary">Price: Low → High</a>
          <a href="?sort=price_desc" class="btn btn-sm btn-outline-secondary">Price: High → Low</a>
        </div>
      </div>

      <div class="col-md-3 text-end mt-2 mt-md-0">
        <small class="text-muted">Showing <strong><?= count($products) ?></strong> products</small>
      </div>
    </div>
  </section>

  <!-- Product grid -->
  <section class="product-grid">
    <div class="row g-4">
      <?php if (empty($products)): ?>
        <div class="col-12">
          <div class="alert alert-warning">No products found.</div>
        </div>
      <?php else: ?>
        <?php foreach ($products as $p): ?>
          <div class="col-6 col-md-4 col-lg-3"> <!-- Bootstrap 4-per-row on lg -->
            <article class="product-card h-100">
              <div class="img-wrap">
                <a href="product-details.php?id=<?= htmlspecialchars($p['id']) ?>" title="<?= htmlspecialchars($p['title']) ?>">
                  <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['title']) ?>" loading="lazy">
                </a>
              </div>

              <div class="card-body">
                <a href="product-details.php?id=<?= htmlspecialchars($p['id']) ?>" class="text-decoration-none">
                  <div class="product-title"><?= htmlspecialchars($p['title']) ?></div>
                </a>
                <div class="product-sub"><?= htmlspecialchars($p['subtitle'] ?? '') ?></div>

                <div class="product-meta">
                  <div>
                    <div class="price">₹ <?= number_format((float)$p['price'], 0) ?></div>
                    <div class="text-muted small">Inclusive of taxes</div>
                  </div>

                  <div class="text-end">
                    <div class="rating"><?= number_format((float)$p['rating'] ?? 0, 1) ?> ★</div>
                    <div class="mt-2">
                      <a href="product-details.php?id=<?= htmlspecialchars($p['id']) ?>" class="btn btn-forest-outline btn-sm">View</a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card-footer text-center border-top">
                <a href="cart-add.php?id=<?= htmlspecialchars($p['id']) ?>" class="btn btn-forest-outline w-100">Add to Cart</a>
              </div>
            </article>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <!-- Pagination (server-side) -->
  <nav aria-label="Page navigation" class="mt-5">
    <ul class="pagination justify-content-center">
      <li class="page-item disabled"><a class="page-link" href="#">«</a></li>
      <li class="page-item active"><a class="page-link" href="#">1</a></li>
      <li class="page-item"><a class="page-link" href="?page=2">2</a></li>
      <li class="page-item"><a class="page-link" href="?page=3">3</a></li>
      <li class="page-item"><a class="page-link" href="?page=2">»</a></li>
    </ul>
  </nav>
</main>


<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>