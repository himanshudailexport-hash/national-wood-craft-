<?php
// admin/add.php
require "admin-auth.php"; 
include "../config/db.php";
include "helpers.php";

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blogtitle = trim($_POST['blogtitle'] ?? '');
    $metatitle = trim($_POST['metatitle'] ?? '');
    $metadescription = trim($_POST['metadescription'] ?? '');
    $blogcontent = trim($_POST['blogcontent'] ?? '');
    $slug = trim($_POST['slug'] ?? '') ?: slugify($blogtitle);

    if (!$blogtitle || !$blogcontent) $errors[] = 'Title and content are required.';

    // Handle image
    $imageFileName = null;
    if (!empty($_FILES['blog_image']['name'])) {
        $uploadDir = __DIR__ . '/../uploads/blog/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $tmp = $_FILES['blog_image']['tmp_name'];
        $origName = basename($_FILES['blog_image']['name']);
        $allowed = ['jpg','jpeg','png','webp','gif'];
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) $errors[] = 'Only images allowed (jpg, png, webp, gif , jpeg).';

        if (empty($errors)) {
            $imageFileName = unique_filename($origName, $uploadDir);
            $target = $uploadDir . $imageFileName;
            if (!move_uploaded_file($tmp, $target)) {
                $errors[] = 'Failed to move uploaded file.';
            }
        }
    }

    // Insert if no errors
    if (empty($errors)) {
        $stmt = $con->prepare("INSERT INTO blogs (slug, metatitle, metadescription, blogtitle, blogcontent, blog_image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss', $slug, $metatitle, $metadescription, $blogtitle, $blogcontent, $imageFileName);
        if ($stmt->execute()) {
            header('Location: blog-management.php');
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
  <title>Add Blog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- custom css  -->
  <link href="dashboard.css" rel="stylesheet">

  <script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>

</head>
<body class="bg-light">
<div class="container py-4">
  <a href="dashboard.php" class="btn btn-secondary mb-3">← Back</a>
  <h3>Add New Blog</h3>

  <?php if($errors): foreach($errors as $e): ?>
    <div class="alert alert-danger"><?=htmlspecialchars($e)?></div>
  <?php endforeach; endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label>Blog Title</label>
      <input name="blogtitle" class="form-control" value="<?=htmlspecialchars($_POST['blogtitle'] ?? '')?>">
    </div>

    <div class="mb-3">
      <label>Slug (optional)</label>
      <input name="slug" class="form-control" value="<?=htmlspecialchars($_POST['slug'] ?? '')?>">
      <small class="text-muted">If left blank slug will be auto-generated from title.</small>
    </div>

    <div class="mb-3">
      <label>Meta Title</label>
      <input name="metatitle" class="form-control" value="<?=htmlspecialchars($_POST['metatitle'] ?? '')?>">
    </div>

    <div class="mb-3">
      <label>Meta Description</label>
      <textarea name="metadescription" class="form-control"><?=htmlspecialchars($_POST['metadescription'] ?? '')?></textarea>
    </div>

    <div class="mb-3">
    <label>Content</label>
    <textarea name="blogcontent" id="editor" class="form-control" rows="8">
        <?=htmlspecialchars($_POST['blogcontent'] ?? '')?>
    </textarea>
    

   </div>



    <div class="mb-3">
      <label>Featured Image</label>
      <input type="file" name="blog_image" class="form-control">
    </div>

    <button class="btn btn-primary">Publish</button>
  </form>
</div>


<script>
    CKEDITOR.replace('editor', {
        height: 300,
        extraPlugins: 'image2,colorbutton,font,justify',
        removePlugins: 'resize',
        toolbar: [
            { name: 'clipboard', items: [ 'Undo', 'Redo' ] },
            { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
            { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike' ] },
            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
            { name: 'insert', items: [ 'Image', 'Table' ] },
            { name: 'links', items: [ 'Link', 'Unlink' ] },
            { name: 'tools', items: [ 'Maximize', 'Source' ] }
        ]
    });
</script>
</body>
</html>
