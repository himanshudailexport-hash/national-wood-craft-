<?php
// admin/index.php
require "admin-auth.php"; 
require "admin-auth.php"; 
require_once __DIR__ . '/../config/db.php';

$res = $con->query("SELECT id, blogtitle, slug, blog_image, created_at FROM blogs ORDER BY created_at DESC");
$blogs = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - Blogs</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Blog Posts</h3>
    <a href="add-blog.php" class="btn btn-success">Add New Post</a>
  </div>

  <table class="table table-striped">
    <thead><tr><th>#</th><th>Title</th><th>Image</th><th>Slug</th><th>Created</th><th>Action</th></tr></thead>
    <tbody>
      <?php foreach($blogs as $b): ?>
        <tr>
          <td><?=htmlspecialchars($b['id'])?></td>
          <td><?=htmlspecialchars($b['blogtitle'])?></td>
          <td>
            <?php if($b['blog_image']): ?>
              <img src="../uploads/blog/<?=htmlspecialchars($b['blog_image'])?>" width="120" style="object-fit:cover;border-radius:6px">
            <?php endif; ?>
          </td>
          <td><?=htmlspecialchars($b['slug'])?></td>
          <td><?=htmlspecialchars($b['created_at'])?></td>
          <td>
            <a class="btn btn-sm btn-primary" href="update-blog.php?id=<?= $b['id'] ?>">Edit</a>
            <form action="delete-blog.php" method="post" style="display:inline" onsubmit="return confirm('Delete this post?')">
              <input type="hidden" name="id" value="<?= $b['id'] ?>">
              <button class="btn btn-sm btn-danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
