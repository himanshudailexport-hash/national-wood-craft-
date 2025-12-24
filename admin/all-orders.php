<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit;
}

include "../config/db.php";

// Fetch all orders (latest first)
$stmt = $con->prepare("SELECT * FROM orders ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Orders</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            /* background: #F7F3EB; */
            color: #2B2926;
            font-family: "Segoe UI", sans-serif; 
        }

        h2 {
            font-weight: 600;
        }

        .table thead {
            background: #3B5B4C;
            color: #fff;
        }

        .badge {
            padding: 6px 10px;
            font-size: 13px;
        }

        .badge-pending { background: #C3A887; }
        .badge-processing { background: #7A5938; }
        .badge-completed { background: #3B5B4C; }
        .badge-cancelled { background: #dc3545; }

        /* ---------- Mobile Responsive Styles ---------- */
        @media (max-width: 768px) {
            .table thead {
                display: none;
            }

            .table,
            .table tbody,
            .table tr,
            .table td {
                display: block;
                width: 100%;
            }

            .table tr {
                background: #fff;
                margin-bottom: 15px;
                border-radius: 10px;
                padding: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            }

            .table td {
                border: none;
                padding: 6px 0;
                font-size: 15px;
            }

            .table td::before {
                content: attr(data-label);
                font-weight: 600;
                display: block;
                color: #6b6b6b;
                margin-bottom: 2px;
            }

            .action-btn {
                margin-top: 10px;
            }
        }
    </style>
</head>

<body>

<div class="container py-5">

    <h2 class="mb-4">All Orders</h2>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Email / Phone</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Order Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            <?php if ($result->num_rows > 0): ?>
                <?php while ($order = $result->fetch_assoc()): ?>

                    <?php
                        $status = strtolower($order['order_status']);
                        $badgeClass = match ($status) {
                            'completed' => 'badge-completed',
                            'processing' => 'badge-processing',
                            'cancelled' => 'badge-cancelled',
                            default => 'badge-pending',
                        };
                    ?>

                    <tr>
                        <td data-label="Customer">
                            <?= htmlspecialchars($order['full_name']) ?>
                        </td>

                        <td data-label="Email / Phone">
                            <?= htmlspecialchars($order['email']) ?><br>
                            <small><?= htmlspecialchars($order['phone']) ?></small>
                        </td>

                        <td data-label="Total">
                            ₹<?= number_format($order['total_amount'], 2) ?>
                        </td>

                        <td data-label="Payment">
                            <?= htmlspecialchars($order['payment_method']) ?>
                        </td>

                        <td data-label="Status">
                            <span class="badge <?= $badgeClass ?>">
                                <?= htmlspecialchars($order['order_status']) ?>
                            </span>
                        </td>

                        <td data-label="Order Date">
                            <?= date("d M Y", strtotime($order['created_at'])) ?>
                        </td>

                        <td data-label="Action" class="action-btn">
                            <a href="order-view.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-dark w-100">
                                View
                            </a>
                        </td>
                    </tr>

                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No orders found</td>
                </tr>
            <?php endif; ?>

            </tbody>
        </table>
    </div>

</div>

</body>
</html>
