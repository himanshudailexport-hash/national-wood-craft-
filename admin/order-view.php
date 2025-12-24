<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit;
}

include "../config/db.php";

$id = intval($_GET['id'] ?? 0);

$stmt = $con->prepare("SELECT * FROM orders WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo "Order not found";
    exit;
}

$status = strtolower($order['order_status']);
$badgeClass = match ($status) {
    'completed' => 'success',
    'processing' => 'warning',
    'cancelled' => 'danger',
    default => 'secondary',
};
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order #<?= $order['id'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #F7F3EB;
            color: #2B2926;
        }
        .card {
            border-radius: 14px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #3B5B4C;
        }
        .info-label {
            font-weight: 600;
            color: #555;
        }
        .info-value {
            color: #222;
        }
    </style>
</head>

<body>

<div class="container py-5">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="mb-0">Order #<?= $order['id'] ?></h3>

        <span class="badge bg-<?= $badgeClass ?> px-3 py-2">
            <?= htmlspecialchars($order['order_status']) ?>
        </span>
    </div>

    <!-- Order Details -->
    <div class="card mb-4">
        <div class="card-body">

            <div class="row g-4">

                <!-- Customer Info -->
                <div class="col-md-6">
                    <div class="section-title">Customer Information</div>

                    <p>
                        <span class="info-label">Name:</span><br>
                        <span class="info-value"><?= htmlspecialchars($order['full_name']) ?></span>
                    </p>

                    <p>
                        <span class="info-label">Email:</span><br>
                        <span class="info-value"><?= htmlspecialchars($order['email']) ?></span>
                    </p>

                    <p>
                        <span class="info-label">Phone:</span><br>
                        <span class="info-value"><?= htmlspecialchars($order['phone']) ?></span>
                    </p>
                </div>

                <!-- Order Info -->
                <div class="col-md-6">
                    <div class="section-title">Order Information</div>

                    <p>
                        <span class="info-label">Order Date:</span><br>
                        <span class="info-value">
                            <?= date("d M Y, h:i A", strtotime($order['created_at'])) ?>
                        </span>
                    </p>

                    <p>
                        <span class="info-label">Payment Method:</span><br>
                        <span class="info-value"><?= htmlspecialchars($order['payment_method']) ?></span>
                    </p>

                    <p>
                        <span class="info-label">Total Amount:</span><br>
                        <span class="fs-5 fw-bold text-success">
                            ₹<?= number_format($order['total_amount'], 2) ?>
                        </span>
                    </p>
                </div>

            </div>
        </div>
    </div>

    <!-- Address -->
    <div class="card">
        <div class="card-body">
            <div class="section-title">Shipping Address</div>
            <p class="mb-0"><?= nl2br(htmlspecialchars($order['address'])) ?></p>
        </div>
    </div>

</div>

</body>
</html>
