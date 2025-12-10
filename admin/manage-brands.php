<?php
require_once(__DIR__ . '/../config/db.php'); 

require "admin-auth.php"; 


// Add New Brand
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_brand'])) {
    $name = trim($_POST['brand_name']);
    if ($name !== '') {
        $stmt = $con->prepare("INSERT INTO brands (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
    }
}

// Update Brand
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_brand'])) {
    $id = intval($_POST['brand_id']);
    $name = trim($_POST['brand_name']);
    if ($name !== '') {
        $stmt = $con->prepare("UPDATE brands SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Delete Brand
if (isset($_GET['delete_brand'])) {
    $id = intval($_GET['delete_brand']);
    $stmt = $con->prepare("DELETE FROM brands WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Fetch Brands
$brands = $con->query("SELECT * FROM brands ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Brands | Admin Panel</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 20px;
        }
        .edit-form-row {
            display: none;
        }
        .btn-sand {
            background-color: #d4a373;
            color: white;
            border: none;
        }
        .btn-sand:hover {
            background-color: #b5835a;
        }
        .text-wood-brown {
            color: #8b5e3c;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <nav class="navbar navbar-expand-lg shadow-sm py-3 px-4 bg-white">
        <div class="container-fluid">
            <a href="dashboard.php" class="btn btn-sand me-3"><i class="fa-solid fa-arrow-left"></i> Back</a>
            <h5 class="fw-bold mb-0 text-wood-brown">Manage Brands</h5>
            <div class="d-flex align-items-center ms-auto">
                <span class="fw-semibold text-dark me-3">Hello, Admin</span>
                <img src="../assets/img/admin-avatar.png" class="rounded-circle" width="40" height="40" alt="">
            </div>
        </div>
    </nav>

    <!-- MAIN -->
    <section class="container py-5">
        <div class="card shadow-sm border-0 rounded-4 p-4">
            <h4 class="fw-bold text-wood-brown mb-4"><i class="fa-solid fa-tags me-2"></i> Add New Brand</h4>

            <!-- ADD BRAND FORM -->
            <form method="POST" class="mb-5">
                <div class="input-group">
                    <input type="text" name="brand_name" class="form-control" placeholder="Enter Brand Name" required>
                    <button type="submit" name="add_brand" class="btn btn-sand px-4"><i class="fa-solid fa-plus"></i> Add</button>
                </div>
            </form>

            <!-- EXISTING BRANDS -->
            <h4 class="fw-bold text-wood-brown mb-3"><i class="fa-solid fa-list me-2"></i> Existing Brands</h4>
            <div class="table-responsive">
                <table class="table align-middle table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Brand Name</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($brand = $brands->fetch_assoc()) { ?>
                            <tr id="brand-row-<?= $brand['id'] ?>">
                                <td><?= htmlspecialchars($brand['id']) ?></td>
                                <td><?= htmlspecialchars($brand['name']) ?></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-primary btn-sm me-2" onclick="toggleEditForm(<?= $brand['id'] ?>)">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </button>
                                    <a href="?delete_brand=<?= $brand['id'] ?>"
                                       onclick="return confirm('Are you sure you want to delete this brand?')"
                                       class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>

                            <!-- EDIT FORM -->
                            <tr id="edit-form-row-<?= $brand['id'] ?>" class="edit-form-row">
                                <td colspan="3">
                                    <div class="card card-body bg-light mb-3">
                                        <h5 class="card-title text-wood-brown">
                                            <i class="fa-solid fa-pen-to-square me-2"></i>Edit Brand ID: <?= htmlspecialchars($brand['id']) ?>
                                        </h5>
                                        <form method="POST">
                                            <input type="hidden" name="brand_id" value="<?= htmlspecialchars($brand['id']) ?>">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Brand Name</label>
                                                <input type="text" name="brand_name" value="<?= htmlspecialchars($brand['name']) ?>" class="form-control" required>
                                            </div>
                                            <button type="submit" name="update_brand" class="btn btn-success me-2">
                                                <i class="fa-solid fa-floppy-disk"></i> Save
                                            </button>
                                            <button type="button" class="btn btn-secondary" onclick="toggleEditForm(<?= $brand['id'] ?>)">Cancel</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleEditForm(id) {
            const row = document.getElementById('brand-row-' + id);
            const editRow = document.getElementById('edit-form-row-' + id);

            if (editRow.style.display === 'none' || editRow.style.display === '') {
                row.style.display = 'none';
                editRow.style.display = 'table-row';
            } else {
                row.style.display = 'table-row';
                editRow.style.display = 'none';
            }
        }
    </script>

</body>
</html>
