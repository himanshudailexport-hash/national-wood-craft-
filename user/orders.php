<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- FONT AWESOME -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">



    <!-- custom css -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        :root {
            --bg-light: #F7F3EB;
            --text-dark: #2B2926;
            --wood-brown: #7A5938;
            --sand: #C3A887;
            --forest: #3B5B4C;
        }

        body {
            background: var(--bg-light);
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
        }

        .text-wood-brown {
            color: var(--wood-brown);
        }

        .text-dark {
            color: var(--text-dark);
        }

        .bg-forest {
            background-color: var(--forest) !important;
        }

        .bg-sand {
            background-color: var(--sand) !important;
        }

        .order-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* Order Card */
        .order-card {
            background: #fff;
            border-radius: 15px;
            padding: 1.5rem;
            border-left: 6px solid var(--sand);
            transition: all 0.25s ease;
        }

        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            border-left-color: var(--forest);
        }

        .order-header h5 {
            color: var(--forest);
        }

        .order-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid var(--sand);
        }

        .order-footer {
            font-size: 0.95rem;
        }

        .btn-sand {
            background-color: var(--sand);
            color: var(--text-dark);
            border: none;
            border-radius: 8px;
            transition: 0.3s ease;
        }

        .btn-sand:hover {
            background-color: var(--forest);
            color: #fff;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .order-card {
                padding: 1rem;
            }

            .order-img {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>

<body>

    <?php include '../components/Header.php' ?>

    <main class="container my-5">
        <h2 class="text-center mb-4 fw-bold text-wood-brown">My Order History</h2>

        <div class="order-list">

            <!-- Single Order Card -->
            <div class="order-card shadow-sm">
                <div class="order-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-semibold">Order #1023</h5>
                        <small class="text-muted">Placed on: Oct 10, 2025</small>
                    </div>
                    <span class="badge bg-forest text-white px-3 py-2">Delivered</span>
                </div>

                <div class="order-body mt-3">
                    <div class="d-flex align-items-center mb-3">
                        <img src="assets/image/Picture1.jpg" alt="Wooden Vase" class="order-img me-3">
                        <div>
                            <h6 class="mb-1 text-wood-brown">Handcrafted Wooden Vase</h6>
                            <p class="text-muted small mb-0">Quantity: 1 | ₹1,200</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <img src="assets/image/Picture2.jpg" alt="Rustic Lamp" class="order-img me-3">
                        <div>
                            <h6 class="mb-1 text-wood-brown">Rustic Wooden Lamp</h6>
                            <p class="text-muted small mb-0">Quantity: 2 | ₹4,000</p>
                        </div>
                    </div>
                </div>

                <div class="order-footer d-flex justify-content-between align-items-center border-top pt-3">
                    <span class="fw-semibold text-dark">Total: ₹5,200</span>
                    <button class="btn btn-sand btn-sm"><i class="fa-solid fa-eye me-1"></i> View Details</button>
                </div>
            </div>

            <!-- Another Order -->
            <div class="order-card shadow-sm">
                <div class="order-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-semibold">Order #1022</h5>
                        <small class="text-muted">Placed on: Sep 28, 2025</small>
                    </div>
                    <span class="badge bg-sand text-dark px-3 py-2">Shipped</span>
                </div>

                <div class="order-body mt-3">
                    <div class="d-flex align-items-center mb-3">
                        <img src="assets/image/Picture3.jpg" alt="Side Table" class="order-img me-3">
                        <div>
                            <h6 class="mb-1 text-wood-brown">Handcrafted Side Table</h6>
                            <p class="text-muted small mb-0">Quantity: 1 | ₹3,500</p>
                        </div>
                    </div>
                </div>

                <div class="order-footer d-flex justify-content-between align-items-center border-top pt-3">
                    <span class="fw-semibold text-dark">Total: ₹3,500</span>
                    <button class="btn btn-sand btn-sm"><i class="fa-solid fa-eye me-1"></i> View Details</button>
                </div>
            </div>

        </div>
    </main>

    <?php include '../components/Footer.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>