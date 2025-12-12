<?php
include "config/db.php";

$slug = $_GET['slug'] ?? '';

$stmt = $con->prepare("SELECT * FROM blogs WHERE slug=? LIMIT 1");
$stmt->bind_param("s", $slug);
$stmt->execute();
$blog = $stmt->get_result()->fetch_assoc();

if (!$blog) {
    echo "Blog not found!";
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title><?= htmlspecialchars($blog['blogtitle']) ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .blog-full-content h1,
        .blog-full-content h2,
        .blog-full-content h3,
        .blog-full-content h4 {
            font-weight: 500 !important;
            color: #1a1a1a;
            margin-top: 35px;
            margin-bottom: 15px;
            line-height: 1.35;
        }

        .blog-full-content h1 {
            font-size: 32px;
        }

        .blog-full-content h2 {
            font-size: 28px;
        }

        .blog-full-content h3 {
            font-size: 24px;
        }

        .blog-full-content h4 {
            font-size: 20px;
        }

        .blog-full-content h2::after {
            content: "";
            display: block;
            width: 50px;
            height: 3px;
            margin-top: 8px;
            background: #e0e0e0;
            border-radius: 2px;
        }

        body {
            background: #ffffff;
            color: #2B2926;
            font-family: "Segoe UI", sans-serif;
        }

        .blog-container {
            max-width: 1100px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .blog-date {
            font-size: 15px;
            color: #6b6b6b;
            margin-bottom: 8px;
        }

        .blog-date i {
            margin-right: 6px;
            color: #3B5B4C;
        }

        .blog-title {
            font-size: 40px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 25px;
        }


        .blog-img-wrapper {
            width: 100%;
            max-height: 600px;
            background: #f7f7f7;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .blog-img {
            width: 100%;
            height: auto;

            max-height: 90vh;

            object-fit: contain;

            background: #f7f7f7;
            border-radius: 12px;
            margin: 25px 0;
            padding: 10px;

        }
        .blog-meta-desc {
            font-size: 18px;
            color: #555;
            margin: 30px 0;
            line-height: 1.6;
        }

        .blog-full-content {
            font-size: 18px;
            line-height: 1.75;
            color: #2B2926;
        }

        .blog-full-content h2,
        .blog-full-content h3,
        .blog-full-content h4 {
            margin-top: 40px;
            font-weight: 600;
        }

        .blog-full-content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 20px 0;
        }

        .blog-full-content ul li {
            margin-bottom: 10px;
        }


        @media (max-width: 768px) {
            .blog-title {
                font-size: 28px;
            }

            .blog-img-wrapper {
                max-height: 350px;
            }

            .blog-meta-desc,
            .blog-full-content {
                font-size: 16px;
            }
        }
    </style>

</head>

<body>

    <div class="blog-container">

        <!-- Blog Date -->
        <div class="blog-date">
            <i class="fa-regular fa-calendar"></i>
            <?= date("F d, Y", strtotime($blog['created_at'])) ?>
        </div>

        <!-- Blog Title -->
        <h1 class="blog-title"><?= htmlspecialchars($blog['blogtitle']) ?></h1>

        <!-- Image Wrapper ensures full image is shown -->
        <div class="blog-img-wrapper">
            <img src="uploads/blog/<?= $blog['blog_image'] ?>" class="blog-img" alt="Blog Image">
        </div>

        <!-- Meta Description -->
        <p class="blog-meta-desc"><?= htmlspecialchars($blog['metadescription']) ?></p>

        <!-- Full Content -->
        <div class="blog-full-content">
            <?= $blog['blogcontent'] ?>
        </div>

    </div>

</body>

</html>