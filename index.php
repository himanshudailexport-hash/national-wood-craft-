<?php
@session_start();
include 'config/db.php';

$sql = "SELECT p.*, c.name AS category_name
        FROM products p
        LEFT JOIN categories c ON p.category = c.id";
$result = $con->query($sql);

$sqlblog = "SELECT id, slug, blogtitle, metadescription, blog_image 
        FROM blogs 
        ORDER BY created_at DESC 
        LIMIT 3";

$resultblog = $con->query($sqlblog);

$categoryQuery = $con->query("SELECT id, name FROM categories ORDER BY name ASC");
$categories = $categoryQuery->fetch_all(MYSQLI_ASSOC);


$catQuery = $con->prepare("SELECT id, name, image_path FROM categories ORDER BY id DESC Limit 4");
$catQuery->execute();
$catResult = $catQuery->get_result();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $_SESSION["contact_error"] = "All fields are required.";
        header("Location: index.php");
        exit();
    }

    $stmt = $con->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        $_SESSION["contact_success"] = "Your message has been sent successfully!";
    } else {
        $_SESSION["contact_error"] = "Failed to send your message. Please try again.";
    }

    header("Location: index.php");
    exit();
}



?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NATIONAL WOOD CRAFT</title>

    <link rel="apple-touch-icon" href="assets/image/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="assets/image/favicon.ico">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- FONT AWESOME -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- custom css -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div>

        <?php include 'components/Header.php' ?>
        <!-- hero section start from here -->
        <section class="container hero-section py-5">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-4 fw-bold text-wood-brown">Premium Mango Wood Handicrafts</h1>
                    <p class="lead">Handmade wooden decor crafted by skilled artisans. Each piece is unique, natural and timeless.</p>
                    <a href="product.php" class="btn btn-forest btn-lg">Shop Now</a>
                </div>
                <div class="col-md-6 text-center">
                    <img src="assets/image/front.png" class="img-fluid hero-img" alt="wood shelf">
                </div>
            </div>
        </section>
        <hr>
        <!-- category section  -->
        <section class="container mb-4">
            <!-- <h2 class="text-center text-wood-brown mb-5 fw-bold">Shop by Category</h2> -->
            <div class="row g-4 justify-content-center">

                <?php
                while ($cat = $catResult->fetch_assoc()) {
                    $imgPath = ltrim($cat['image_path'], './');
                ?>

                    <div class="col-md-3 col-sm-6" >
                        <a href="product.php?category=<?php echo $cat['id']; ?>" style="text-decoration:none;">
                            <div class="category-mini-card">
                                <img src="<?php echo $imgPath; ?>" alt="<?php echo $cat['name']; ?>">
                                <h5><?php echo $cat['name']; ?></h5>
                            </div>
                        </a>
                    </div>

                <?php } ?>

            </div>
        </section>

        <!-- ABOUT HERO -->
        <section class="about-hero py-5">
            <div class="container text-center" >
                <h1 class="fw-bold mb-3 text-wood-brown">About Our Wooden Handicrafts</h1>
                <p class="lead text-muted mx-auto about-intro">
                    We craft premium wooden art with a deep connection to Indian heritage and design.
                    <!-- Every product is handmade with precision, passion, and emotions that turn wood into art. -->
                </p>
            </div>
            <section class="py-5">
                <div class="container">
                    <div class="row align-items-center">

                        <div class="col-md-6 mb-4" >
                            <img src="assets/image/about.png" class="img-fluid rounded-4 shadow about-img" alt="About Wooden Handicrafts">
                        </div>

                        <div class="col-md-6" >
                            <h3 class="fw-bold text-wood-brown">Crafted With Natural Class</h3>
                            <p class="text-muted">
                                National Wood Craft is a trusted manufacturer of premium mango and sheesham wood decor and utility products. With a focus on fine craftsmanship, elegant finishing and long-lasting quality, we create products that enhance every home. Our wide range includes candle holders, wall decor, serving ware and organizers, supplied consistently to wholesalers, retailers and bulk buyers across regions, ensuring reliable quality, safe packaging and timely delivery for every order.
                            </p>
                            <p class="text-muted">
                                Our team of master artisans make each piece by hand using ethically sourced wood.
                                We support Indian craft communities and bring global-level wooden home décor products to you.
                            </p>


                            <ul class="list-unstyled text-muted">
                                <li class="mb-2"><i class="fa-solid fa-check text-forest me-2"></i>100% Handmade premium quality</li>
                                <li class="mb-2"><i class="fa-solid fa-leaf text-forest me-2"></i>Sustainable & environment friendly</li>
                                <li class="mb-2"><i class="fa-solid fa-star text-forest me-2"></i>Designed following global luxury standards</li>
                            </ul>
                            <!-- <div class="text-center"> -->
                            <a href="about.php" class="btn btn-forest px-4">
                                View More
                            </a>
                            <!-- </div> -->

                        </div>

                    </div>
                </div>
            </section>
        </section>
        <!-- counting -->
        <!-- <section class="stats-section">
            <div class="stat">
                <h2 class="count" data-target="1500">0</h2>
                <p>Happy Customers</p>
            </div>

            <div class="stat">
                <h2 class="count" data-target="120">0</h2>
                <p>Order Delevered </p>
            </div>

            <div class="stat">
                <h2 class="count" data-target="10">0</h2>
                <p>Years Experience</p>
            </div>
        </section> -->

        

        <!-- <script>
            const counters = document.querySelectorAll('.count');
            let started = false;

            function startCounting() {
                if (started) return;
                started = true;

                counters.forEach(counter => {
                    const target = +counter.getAttribute('data-target');
                    const speed = 200; // lower = faster

                    const updateCount = () => {
                        const current = +counter.innerText.replace('+', '');
                        const increment = Math.ceil(target / speed);

                        if (current < target) {
                            counter.innerText = current + increment;
                            setTimeout(updateCount, 20);
                        } else {
                            counter.innerText = target + "+"; // 👈 add +
                        }
                    };

                    updateCount();
                });
            }

            // Trigger when section is visible
            window.addEventListener('scroll', () => {
                const section = document.querySelector('.stats-section');
                const sectionTop = section.getBoundingClientRect().top;
                const screenHeight = window.innerHeight;

                if (sectionTop < screenHeight - 100) {
                    startCounting();
                }
            });
        </script> -->


        <!-- products   -->
        <section class="category-section pt-5 text-center">
            <div class="container">
                <h1 class="page-title  text-wood-brown fw-bold">Our Products</h1>


            </div>
        </section>

        <section class="product-section py-4">
            <div class="container">
                <div class="row g-4 justify-content-center">

                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {

                            $name = htmlspecialchars($row['name']);
                            $price = htmlspecialchars($row['price']);
                            $image = htmlspecialchars($row['image1']);
                            $category_id = htmlspecialchars($row['category']);
                            $category_name = htmlspecialchars($row['category_name']);
                    ?>
                            <div class="col-md-3 col-sm-6 product-card" data-category="<?= $category_id ?>">
                                <div class="card border-0 shadow-sm product-box h-100">
                                    <img src="<?= $image ?>" class="card-img-top rounded-top-4" alt="<?= $name ?>">

                                    <div class="product-info text-center p-3">
                                        <h3 class="fw-semibold text-wood-brown fs-6"><?= $name ?></h3>
                                        <p class="price fw-bold text-forest mb-1">₹<?= $price ?></p>
                                        <p class="text-muted small mb-3"><?= $category_name ?></p>

                                        <div class="d-flex gap-3">
                                            <button
                                                class="btn btn-add w-100 add-to-cart"
                                                data-id="<?= $row['id'] ?>"
                                                data-name="<?= $name ?>"
                                                data-price="<?= $price ?>"
                                                data-image="<?= $image ?>"
                                                data-category="<?= $category_name ?>">
                                                <i class="fa-solid fa-cart-plus me-2"></i> Add
                                            </button>

                                            <a href="products-detail.php?id=<?= $row['id'] ?>" class="btn btn-add-forest w-100">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    <?php
                        }
                    } else {
                        echo "<p class='text-center text-muted'>No products found.</p>";
                    }
                    ?>

                </div>
            </div>
        </section>

        


        <!-- <script>
            
            const buttons = document.querySelectorAll('.category-btn');
            const products = document.querySelectorAll('.product-card');

            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    buttons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    const category = btn.dataset.category;

                    products.forEach(prod => {
                        prod.style.display = category === 'all' || prod.dataset.category === category ?
                            'block' :
                            'none';
                    });
                });
            });


            function loadCart() {
                return JSON.parse(localStorage.getItem("cartItems")) || [];
            }

            function saveCart(cart) {
                localStorage.setItem("cartItems", JSON.stringify(cart));

                window.dispatchEvent(new Event("cart-updated"));
            }

            // ADD TO CART
            document.querySelectorAll(".add-to-cart").forEach(btn => {
                btn.addEventListener("click", () => {

                    let cart = loadCart();

                    let product = {
                        id: btn.dataset.id,
                        name: btn.dataset.name,
                        price: parseFloat(btn.dataset.price),
                        image: btn.dataset.image,
                        category: btn.dataset.category,
                        qty: 1
                    };

                    // If product exists, increase qty
                    let existing = cart.find(item => item.id === product.id);

                    if (existing) {
                        existing.qty += 1;
                    } else {
                        cart.push(product);
                    }

                    saveCart(cart);

                    alert("Added to cart!");
                });
            });
        </script> -->






        <!-- contact page  -->
        <!-- ALERT MESSAGES -->
        <div class="container alert-position">
            <?php if (isset($_SESSION["contact_success"])) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION["contact_success"]; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php unset($_SESSION["contact_success"]);
            } ?>

            <?php if (isset($_SESSION["contact_error"])) { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION["contact_error"]; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php unset($_SESSION["contact_error"]);
            } ?>
        </div>

        <!-- <section class="strength-section">
            <div class="container">

                <h2 class="section-title">Our Strengths</h2>

                <div class="strength-grid">

                    <div class="strength-card">
                        <div class="icon">
                            <i class="fa-solid fa-hammer"></i>
                        </div>
                        <h4>Handcrafted Excellence</h4>
                        <p>
                            Each product is carefully handmade by skilled artisans using
                            traditional woodworking techniques.
                        </p>
                    </div>

                    <div class="strength-card">
                        <div class="icon">
                            <i class="fa-solid fa-tree"></i>
                        </div>
                        <h4>Premium Quality Wood</h4>
                        <p>
                            We use responsibly sourced, high-quality wood to ensure durability
                            and timeless beauty.
                        </p>
                    </div>

                    <div class="strength-card">
                        <div class="icon">
                            <i class="fa-solid fa-leaf"></i>
                        </div>
                        <h4>Sustainable Craft</h4>
                        <p>
                            Our process respects nature by minimizing waste and promoting
                            eco-friendly craftsmanship.
                        </p>
                    </div>

                    <div class="strength-card">
                        <div class="icon">
                            <i class="fa-solid fa-pen-ruler"></i>
                        </div>
                        <h4>Custom Designs</h4>
                        <p>
                            We offer personalized designs tailored to your space, style, and
                            functional needs.
                        </p>
                    </div>

                </div>

                <div class="strength-grid">

                    <div class="strength-card">
                        <div class="icon">
                            <i class="fa-solid fa-hammer"></i>
                        </div>
                        <h4>Handcrafted Excellence</h4>
                        <p>
                            Each product is carefully handmade by skilled artisans using
                            traditional woodworking techniques.
                        </p>
                    </div>

                    <div class="strength-card">
                        <div class="icon">
                            <i class="fa-solid fa-tree"></i>
                        </div>
                        <h4>Premium Quality Wood</h4>
                        <p>
                            We use responsibly sourced, high-quality wood to ensure durability
                            and timeless beauty.
                        </p>
                    </div>

                    <div class="strength-card">
                        <div class="icon">
                            <i class="fa-solid fa-leaf"></i>
                        </div>
                        <h4>Sustainable Craft</h4>
                        <p>
                            Our process respects nature by minimizing waste and promoting
                            eco-friendly craftsmanship.
                        </p>
                    </div>

                    <div class="strength-card">
                        <div class="icon">
                            <i class="fa-solid fa-pen-ruler"></i>
                        </div>
                        <h4>Custom Designs</h4>
                        <p>
                            We offer personalized designs tailored to your space, style, and
                            functional needs.
                        </p>
                    </div>

                </div>
            </div>
        </section> -->

        <section class="strength-section">
            <div class="container">

                <h2 class="section-title">Our Strengths</h2>

                <div class="strength-grid">

                    <div class="strength-card">
                        <div class="icon"><i class="fa-solid fa-hammer"></i></div>
                        <h4>Handcrafted Excellence</h4>
                        <p>Every product is handmade by skilled artisans.</p>
                    </div>

                    <div class="strength-card">
                        <div class="icon"><i class="fa-solid fa-tree"></i></div>
                        <h4>Premium Wood</h4>
                        <p>High-quality, durable, and responsibly sourced wood.</p>
                    </div>

                    <div class="strength-card">
                        <div class="icon"><i class="fa-solid fa-leaf"></i></div>
                        <h4>Eco-Friendly</h4>
                        <p>Sustainable processes that respect nature.</p>
                    </div>

                    <div class="strength-card">
                        <div class="icon"><i class="fa-solid fa-pen-ruler"></i></div>
                        <h4>Custom Designs</h4>
                        <p>Made-to-order designs tailored to your needs.</p>
                    </div>

                    <div class="strength-card">
                        <div class="icon"><i class="fa-solid fa-award"></i></div>
                        <h4>Quality Finish</h4>
                        <p>Fine polishing for a premium look & feel.</p>
                    </div>

                    <div class="strength-card">
                        <div class="icon"><i class="fa-solid fa-box"></i></div>
                        <h4>Secure Packaging</h4>
                        <p>Safe packaging to prevent transit damage.</p>
                    </div>

                    <div class="strength-card">
                        <div class="icon"><i class="fa-solid fa-truck-fast"></i></div>
                        <h4>Timely Delivery</h4>
                        <p>Reliable and fast product delivery.</p>
                    </div>

                    <div class="strength-card">
                        <div class="icon"><i class="fa-solid fa-thumbs-up"></i></div>
                        <h4>Customer Satisfaction</h4>
                        <p>Trusted by customers across India.</p>
                    </div>

                </div>
            </div>
        </section>



        <section class="blog-section py-5">
            <div class="container">
                <h2 class="section-title text-center mb-4" data-aos="zoom-in-down" data-aos-delay="300">Latest Blogs</h2>

                <div class="row g-4">

                    <?php while ($b = $resultblog->fetch_assoc()): ?>
                        <div class="col-md-4" data-aos="flip-down" data-aos-delay="300">
                            <div class="blog-card shadow-sm">
                                <img src="uploads/blog/<?= htmlspecialchars($b['blog_image']) ?>"
                                    class="img-fluid blog-img" alt="Blog Image">

                                <div class="blog-content p-3">
                                    <h4 class="blog-title"><?= htmlspecialchars($b['blogtitle']) ?></h4>

                                    <p class="blog-desc text-muted">
                                        <?= htmlspecialchars(substr($b['metadescription'], 0, 120)) ?>...
                                    </p>

                                    <a href="blog-details.php?slug=<?= $b['slug'] ?>" class="btn btn-forest">
                                        Read More
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>

                </div>
            </div>
        </section>
        <!-- WHY CHOOSE US -->
        <section class="why-choose-us py-5">
            <div class="text-center mb-4" data-aos="fade-buttom" data-aos-delay="300">
                <h3 class="fw-bold text-wood-brown">Why Choose Us</h3>
            </div>

            <div class="card-bg ">
                <div class="container text-center">
                    <div class="row g-4">

                        <div class="col-md-4" data-aos="fade-right">
                            <div class="p-4 border-0 shadow-sm rounded-4 bg-white h-100 card-hover">
                                <i class="fa-solid fa-gem fa-2x mb-3 text-wood-brown"></i>
                                <h5 class="fw-bold">Premium Quality</h5>
                                <p class="text-muted small">We never compromise on finish, durability, and premium feel.</p>
                            </div>
                        </div>

                        <div class="col-md-4" data-aos="fade-up">
                            <div class="p-4 border-0 shadow-sm rounded-4 bg-white h-100 card-hover">
                                <i class="fa-solid fa-hands-holding fa-2x mb-3 text-wood-brown"></i>
                                <h5 class="fw-bold">Authentic Craftsmanship</h5>
                                <p class="text-muted small">Products are made by skilled craft artisans with decades of experience.</p>
                            </div>
                        </div>

                        <div class="col-md-4" data-aos="fade-left">
                            <div class="p-4 border-0 shadow-sm rounded-4 bg-white h-100 card-hover">
                                <i class="fa-solid fa-globe fa-2x mb-3 text-wood-brown"></i>
                                <h5 class="fw-bold">Global Shipping</h5>
                                <p class="text-muted small">Our wooden art goes worldwide with safe packaging.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        
        <!-- Hero Section -->
        <section class="contact-hero py-5 text-center">
            <div class="container" data-aos="fade-buttom" data-aos-delay="300">
                <h1 class="fw-bold mb-3 text-wood-brown">Contact Us</h1>
                <p class="lead mx-auto" style="max-width: 700px;">
                    Have questions about wooden handicrafts? Need custom work or business collaboration? We're here to help you.
                </p>
            </div>


        </section>

        <section class="contact-hero pb-5">
            <div class="container">
                <div class="row g-4">
                    <div class="col-md-5" >
                        <div class="p-4 border rounded-4 shadow-sm bg-white h-100 contact-info">
                            <h4 class="mb-3">Contact Information</h4>

                            <p class="mb-3"><i class="fa-solid fa-location-dot me-2 text-wood-brown"></i><strong>Address:</strong> 11/1073-272, Aali Ki Chungi, Opp. Sabri Ka Bagh,
                                Saharanpur – 247001, (U.P.), India</p>

                            <p class="mb-3"><i class="fa-solid fa-envelope me-2 text-wood-brown"></i><strong>Email:</strong> info@nationalwoodcraft-ab.com</p>

                            <p class="mb-4"><i class="fa-solid fa-phone me-2 text-wood-brown"></i><strong>Phone:</strong> +91 8077661038 , 8077397148</p>

                            <hr>

                            <h6 class="fw-bold mb-2"><i class="fa-regular fa-clock me-2 text-wood-brown"></i>Business Hours</h6>
                            <p class="mb-1">Monday – Thursday : 9 AM – 6 PM</p>
                            <p class="mb-0">Friday : Closed</p>
                        </div>
                    </div>


                    <div class="col-md-7">
                        <div class="p-4 border rounded-4 shadow-sm bg-white contact-form" >
                            <h4 class="mb-4">Send us a Message</h4>

                            <form method="POST" action="contact.php">
                                <div class="row mb-3">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-wood-brown"></i></span>
                                            <input type="text" name="name" class="form-control border-start-0" placeholder="Full Name" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-wood-brown"></i></span>
                                            <input type="email" name="email" class="form-control border-start-0" placeholder="Email Address" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-tag text-wood-brown"></i></span>
                                        <input type="text" name="subject" class="form-control border-start-0" placeholder="Subject" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-message text-wood-brown"></i></span>
                                        <textarea name="message" rows="5" class="form-control border-start-0" placeholder="Write your message..." required></textarea>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-forest w-100 btn-lg fw-semibold">
                                    <i class="fa-solid fa-paper-plane me-2"></i>Send Message
                                </button>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </section>

        
        <!-- Google Map -->
        <section class="contact-hero pb-5">
            <div class="container">
                <div class="overflow-hidden shadow-sm" >

                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m26!1m12!1m3!1d13825.0135665653!2d77.53063059926606!3d29.97214700770978!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m11!3e6!4m3!3m2!1d29.977221399999998!2d77.5325189!4m5!1s0x390eeb00c43a1857%3A0xa052912c9a4ab24!2sAali%20ki%20chungi%20gali%20no%2010%2C%203670%2C%20Purani%20Mandi%2C%20Saharanpur%2C%20Uttar%20Pradesh%20247001!3m2!1d29.9777584!2d77.53334699999999!5e0!3m2!1sen!2sin!4v1764159997361!5m2!1sen!2sin"
                        width="100%" height="360" style="border:0;" allowfullscreen loading="lazy"></iframe>
                </div>
            </div>
        </section>




        <!-- <section class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-4 card" >
                        <img src="assets/image/about.png" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
                            <a href="#" class="btn btn-success">Go somewhere</a>
                        </div>
                    </div>

                    <div class="col-4 card" >
                        <img src="..." class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>

                    <div class="col-4 card" >
                        <img src="..." class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section> -->

        <!-- <section class="blog-section py-5">
            <div class="container">
                <h2 class="section-title text-center mb-4">Latest Blogs</h2>

                <div class="row g-4">

                    
                    <div class="col-md-4">
                        <div class="blog-card shadow-sm">
                            <img src="assets/image/about.png" class="img-fluid blog-img" alt="">
                            <div class="blog-content p-3">
                                <h4 class="blog-title">Blog Title One</h4>
                                <p class="blog-desc text-muted">
                                    Short description of the first blog goes here. It should be around 2–3 lines.
                                </p>
                                <a href="#" class="btn btn-forest">Read More</a>
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-md-4">
                        <div class="blog-card shadow-sm">
                            <img src="uploads/blog/blog2.jpg" class="img-fluid blog-img" alt="">
                            <div class="blog-content p-3">
                                <h4 class="blog-title">Blog Title Two</h4>
                                <p class="blog-desc text-muted">
                                    Short description of the second blog goes here. A clean and modern layout.
                                </p>
                                <a href="#" class="btn btn-forest">Read More</a>
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-md-4">
                        <div class="blog-card shadow-sm">
                            <img src="uploads/blog/blog3.jpg" class="img-fluid blog-img" alt="">
                            <div class="blog-content p-3">
                                <h4 class="blog-title">Blog Title Three</h4>
                                <p class="blog-desc text-muted">
                                    Short teaser text to attract users to read the full article.
                                </p>
                                <a href="#" class="btn btn-forest">Read More</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section> -->








        <?php include 'components/Footer.php' ?>
        <?php include 'components/w-chat.php' ?>

    </div>

    <!-- Bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom js  -->
    <script src="assets/js/main.js"></script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script> -->
    <script>
        AOS.init();
    </script>


</body>

</html>