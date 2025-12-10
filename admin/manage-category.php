

<?php
require_once(__DIR__ . '/../config/db.php');

require "admin-auth.php"; 
// Directory for uploads
$uploadDir = "../uploads/categories/";
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

// Handle Image Upload
function handleImageUpload($fileInputName, $uploadDir, $existingImagePath = null) {
    $imagePath = $existingImagePath;
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES[$fileInputName]['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $allowed)) {
            if ($existingImagePath && file_exists($existingImagePath)) unlink($existingImagePath);
            $newName = uniqid("cat_", true) . "." . $ext;
            $targetPath = $uploadDir . $newName;
            if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetPath)) {
                $imagePath = $targetPath;
            }
        }
    } elseif (isset($_POST['remove_existing_image']) && $_POST['remove_existing_image'] == '1' && $existingImagePath) {
        if (file_exists($existingImagePath)) unlink($existingImagePath);
        $imagePath = null;
    }
    return $imagePath;
}

// Add Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = trim($_POST['category_name']);
    $imagePath = handleImageUpload('category_image', $uploadDir);
    if ($name !== '') {
        $stmt = $con->prepare("INSERT INTO categories (name, image_path) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $imagePath);
        $stmt->execute();
        $stmt->close();
    }
}

// Update Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_category'])) {
    $id = intval($_POST['category_id']);
    $name = trim($_POST['category_name']);
    $stmt = $con->prepare("SELECT image_path FROM categories WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($currentImagePath);
    $stmt->fetch();
    $stmt->close();

    $imagePath = handleImageUpload('category_image_update', $uploadDir, $currentImagePath);
    $stmt = $con->prepare("UPDATE categories SET name=?, image_path=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $imagePath, $id);
    $stmt->execute();
    $stmt->close();
}

// Delete Category
if (isset($_GET['delete_category'])) {
    $id = intval($_GET['delete_category']);
    $stmt = $con->prepare("SELECT image_path FROM categories WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($img);
    $stmt->fetch();
    $stmt->close();
    if ($img && file_exists($img)) unlink($img);
    $stmt = $con->prepare("DELETE FROM categories WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$categories = $con->query("SELECT * FROM categories ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #faf6f1; font-family: 'Poppins', sans-serif; }
        .card { border-radius: 1rem; }
        .table img { border-radius: 10px; max-width: 70px; height: 70px; object-fit: cover; }
        .btn-sand { background-color: #c2a77b; color: white; border: none; }
        .btn-sand:hover { background-color: #b08f61; color: #fff; }
        .text-wood-brown { color: #6b4e2e; }
        .edit-form-row { display: none; }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-white shadow-sm py-3 px-4">
        <div class="container-fluid">
            <a href="dashboard.php" class="btn btn-sand me-3"><i class="fa-solid fa-arrow-left"></i> Back</a>
            <h5 class="fw-bold mb-0 text-wood-brown">Manage Categories</h5>
        </div>
    </nav>

    <section class="container py-5">
        <div class="card shadow-sm border-0 p-4 mb-4">
            <h4 class="fw-bold text-wood-brown mb-4"><i class="fa-solid fa-layer-group me-2"></i> Add New Category</h4>

            <form method="POST" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="category_name" class="form-control" placeholder="Enter category name" required>
                </div>
                <div class="col-md-4">
                    <input type="file" name="category_image" class="form-control" accept="image/*">
                </div>
                <div class="col-md-2 text-end">
                    <button type="submit" name="add_category" class="btn btn-sand w-100">
                        <i class="fa-solid fa-plus"></i> Add
                    </button>
                </div>
            </form>
        </div>

        <div class="card shadow-sm border-0 p-4">
            <h4 class="fw-bold text-wood-brown mb-4"><i class="fa-solid fa-table-list me-2"></i> Existing Categories</h4>

            <div class="table-responsive">
                <table class="table align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Category Name</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($cat = $categories->fetch_assoc()) { ?>
                            <tr id="category-row-<?= $cat['id'] ?>">
                                <td><?= htmlspecialchars($cat['id']) ?></td>
                                <td class=" text-wood-brown"><?= htmlspecialchars($cat['name']) ?></td>
                                <td>
                                    <?php if ($cat['image_path'] && file_exists($cat['image_path'])) { ?>
                                        <img src="<?= htmlspecialchars($cat['image_path']) ?>" alt="<?= htmlspecialchars($cat['name']) ?>">
                                    <?php } else { ?>
                                        <span class="text-muted">No Image</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-outline-primary btn-sm me-2"
                                        onclick="toggleEditForm(<?= $cat['id'] ?>)">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <a href="?delete_category=<?= $cat['id'] ?>" class="btn btn-outline-danger btn-sm"
                                       onclick="return confirm('Delete this category?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Edit Form Row -->
                            <tr id="edit-form-row-<?= $cat['id'] ?>" class="edit-form-row">
                                <td colspan="4">
                                    <div class="card card-body bg-light border-0 shadow-sm">
                                        <form method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="category_id" value="<?= $cat['id'] ?>">
                                            <div class="row g-3 align-items-center">
                                                <div class="col-md-4">
                                                    <label class="form-label">Category Name</label>
                                                    <input type="text" name="category_name" class="form-control"
                                                           value="<?= htmlspecialchars($cat['name']) ?>" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Upload New Image</label>
                                                    <input type="file" name="category_image_update" class="form-control">
                                                    <?php if ($cat['image_path'] && file_exists($cat['image_path'])) { ?>
                                                        <div class="mt-2">
                                                            <img src="<?= htmlspecialchars($cat['image_path']) ?>" class="rounded" style="max-width: 80px;">
                                                            <div class="form-check mt-2">
                                                                <input class="form-check-input" type="checkbox" name="remove_existing_image" value="1">
                                                                <label class="form-check-label">Remove current image</label>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    <button type="submit" name="update_category" class="btn btn-sand me-2">Save</button>
                                                    <button type="button" class="btn btn-secondary" onclick="toggleEditForm(<?= $cat['id'] ?>)">Cancel</button>
                                                </div>
                                            </div>
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

    <script>
        function toggleEditForm(id) {
            const mainRow = document.getElementById('category-row-' + id);
            const editRow = document.getElementById('edit-form-row-' + id);
            if (editRow.style.display === 'none' || editRow.style.display === '') {
                mainRow.style.display = 'none';
                editRow.style.display = 'table-row';
            } else {
                mainRow.style.display = 'table-row';
                editRow.style.display = 'none';
            }
        }
    </script>
</body>
</html>
