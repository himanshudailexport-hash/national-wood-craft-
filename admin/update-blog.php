<?php
// admin/edit.php
require "admin-auth.php"; 

include "../config/db.php";
include "helpers.php";

$id = intval($_GET['id'] ?? 0);
if (!$id) {
  header('Location: test.php');
  exit;
}

$stmt = $con->prepare("SELECT * FROM blogs WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$blog = $res->fetch_assoc();
if (!$blog) {
  header('Location: test.php');
  exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $blogtitle = trim($_POST['blogtitle'] ?? '');
  $metatitle = trim($_POST['metatitle'] ?? '');
  $metadescription = trim($_POST['metadescription'] ?? '');
  $blogcontent = trim($_POST['blogcontent'] ?? '');
  $slug = trim($_POST['slug'] ?? '') ?: slugify($blogtitle);

  if (!$blogtitle || !$blogcontent) $errors[] = 'Title and content are required.';

  // Image replace
  $imageFileName = $blog['blog_image'];
  if (!empty($_FILES['blog_image']['name'])) {
    $uploadDir = __DIR__ . '/../uploads/blog/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $tmp = $_FILES['blog_image']['tmp_name'];
    $origName = basename($_FILES['blog_image']['name']);
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) $errors[] = 'Only images allowed (jpg, png, webp, gif).';

    if (empty($errors)) {
      $newName = unique_filename($origName, $uploadDir);
      $target = $uploadDir . $newName;
      if (move_uploaded_file($tmp, $target)) {
        // delete old file
        if ($imageFileName && file_exists($uploadDir . $imageFileName)) {
          @unlink($uploadDir . $imageFileName);
        }
        $imageFileName = $newName;
      } else {
        $errors[] = 'Failed to upload new image.';
      }
    }
  }

  if (empty($errors)) {
    $stmt = $con->prepare("UPDATE blogs SET slug=?, metatitle=?, metadescription=?, blogtitle=?, blogcontent=?, blog_image=? WHERE id=?");
    $stmt->bind_param('ssssssi', $slug, $metatitle, $metadescription, $blogtitle, $blogcontent, $imageFileName, $id);
    if ($stmt->execute()) {
      header('Location: test.php');
      exit;
    } else {
      $errors[] = 'DB error: ' . $stmt->error;
    }
  }
}
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Edit Blog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- custom css  -->
  <link href="dashboard.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container py-4">
    <a href="test.php" class="btn btn-secondary mb-3">← Back</a>
    <h3>Edit Blog</h3>

    <?php if ($errors): foreach ($errors as $e): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($e) ?></div>
    <?php endforeach;
    endif; ?>

    <form method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Blog Title</label>
        <input name="blogtitle" class="form-control" value="<?= htmlspecialchars($_POST['blogtitle'] ?? $blog['blogtitle']) ?>">
      </div>

      <div class="mb-3">
        <label>Slug (optional)</label>
        <input name="slug" class="form-control" value="<?= htmlspecialchars($_POST['slug'] ?? $blog['slug']) ?>">
      </div>

      <div class="mb-3">
        <label>Meta Title</label>
        <input name="metatitle" class="form-control" value="<?= htmlspecialchars($_POST['metatitle'] ?? $blog['metatitle']) ?>">
      </div>

      <div class="mb-3">
        <label>Meta Description</label>
        <textarea name="metadescription" class="form-control"><?= htmlspecialchars($_POST['metadescription'] ?? $blog['metadescription']) ?></textarea>
      </div>

      <textarea name="blogcontent" id="editor" class="form-control" rows="8">
    <?= htmlspecialchars($_POST['blogcontent'] ?? $blog['blogcontent']) ?>
   </textarea>

      <div class="mb-3">
        <label>Featured Image (leave blank to keep current)</label>
        <?php if ($blog['blog_image']): ?>
          <div class="mb-2">
            <img src="../uploads/blog/<?= htmlspecialchars($blog['blog_image']) ?>" width="200" style="object-fit:cover;border-radius:6px">
          </div>
        <?php endif; ?>
        <input type="file" name="blog_image" class="form-control">
      </div>

      <button class="btn btn-primary">Save Changes</button>
    </form>
  </div>

  <script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
  <script>
    CKEDITOR.replace('editor', {
      height: 300,
      contentsCss: 'blog-editor.css',
      bodyClass: 'editor-body',
    });
  </script>
</body>

</html>