<?php
include "config/db.php";

// Fetch all blogs in DESC order
$qry = $con->query("SELECT * FROM blogs ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Blogs</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include "components/Header.php"; ?>

<section class="blog-section py-5">
    <div class="container">
        <h2 class="section-title text-center mb-4">Latest Blogs</h2>

        <div class="row g-4">

            <?php if ($qry->num_rows > 0): ?>
                <?php while ($blog = $qry->fetch_assoc()): ?>

                    <!-- BLOG CARD -->
                    <div class="col-md-4">
                        <div class="blog-card shadow-sm h-100">

                            <img src="uploads/blog/<?= htmlspecialchars($blog['blog_image']) ?>"
                                 class="img-fluid blog-img"
                                 alt="<?= htmlspecialchars($blog['blogtitle']) ?>">

                            <div class="blog-content p-3">
                                <h4 class="blog-title">
                                    <?= htmlspecialchars($blog['blogtitle']) ?>
                                </h4>

                                <p class="blog-desc text-muted">
                                    <?= htmlspecialchars(substr(strip_tags($blog['blogcontent']), 0, 120)) ?>...
                                </p>

                                <a href="blog-details.php?slug=<?= htmlspecialchars($blog['slug']) ?>"
                                   class="btn btn-forest">
                                    Read More
                                </a>
                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center text-muted">No blogs found.</p>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php include "components/Footer.php"; ?>

</body>
</html>
