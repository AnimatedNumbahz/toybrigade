<?php
include __DIR__ . '/partials/header.php';   // outputs <!DOCTYPE html> + <head> + opens <body>
include 'connection.php';

$page_title = "Toy Brigade | Home";
$page_css   = []; // e.g., ['../css/home.css?v=1'] if you have a home-specific CSS

include __DIR__ . '/partials/navbar.php';   // shared navbar (dropdowns need footer bundle)
?>

<body>
 
  <!-- Hero Carousel -->
  <section id="heroCarousel" class="carousel slide hero" data-bs-ride="carousel" data-bs-interval="4000">
    <!-- Indicators -->
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"
        aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>

    <!-- Slides -->
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="../images/baby-playing.svg" class="d-block w-100" alt="Toy Image 1">
      </div>
      <div class="carousel-item">
        <img src="../images/kids-playing-toys.svg" class="d-block w-100" alt="Toy Image 2">
      </div>
      <div class="carousel-item">
        <img src="../images/toy-store.svg" class="d-block w-100" alt="Toy Image 3">
      </div>
    </div>

    <!-- Overlay text -->
    <div class="carousel-caption d-flex flex-column justify-content-center align-items-center">
      <h1 class="fw-bold hero-title">Welcome to Toy Brigade 🎠</h1>
      <p class="lead">A proudly Filipino toy brand where imagination meets the imaginary. Fun has no age limit!</p>
      <a href="#" class="btn btn-pastel btn-lg mt-3">Shop Now</a>
    </div>

    <!-- Prev/Next controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </section>


  <!-- About / Mission (Full-Height with Dynamic Layout) -->
  <section class="mission-section d-flex align-items-center" style="min-height: 100vh; background-color: #fffafc;">
    <div class="container">
      <div class="row align-items-center">

        <!-- Text Column -->
        <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
          <h2 class="fw-bold section-title display-4">Our Mission</h2>
          <p class="lead mb-3">
            Toy Brigade is a proudly Filipino toy brand that sparks imagination, creativity, and joy for all ages.
            From educational toys for toddlers to collectible figures for enthusiasts, we bring playful experiences to
            life.
          </p>
          <p class="lead mb-3">
            We curate high-quality products blending learning, adventure, and nostalgia. Our goal is to connect a
            community
            of toy lovers—young and old—through purposeful and meaningful play.
          </p>
          <p class="fw-semibold">
            "To empower creativity and connection with a thoughtfully selected collection of toys that enrich play for
            everyone."
          </p>
        </div>

        <!-- Image Column (Collage, Magazine-Style) -->
        <div class="col-lg-6 d-flex flex-wrap justify-content-center gap-3 mission-images-wrapper">
          <img src="../images/pup-toy-baby.svg" alt="Toy Play" class="img-fluid rounded-4 shadow angled-mission"
            style="width: 48%;">
          <img src="../images/mission-2.png" alt="Learning Toy" class="img-fluid rounded-4 shadow angled-mission"
            style="width: 48%;">
          <img src="../images/mission-3.png" alt="Collectibles" class="img-fluid rounded-4 shadow angled-mission"
            style="width: 48%;">
          <img src="../images/mission-4.png" alt="Adventure" class="img-fluid rounded-4 shadow angled-mission"
            style="width: 48%;">
        </div>

      </div>
    </div>
  </section>


  <!-- Explore Categories -->
  <section class="explore-categories position-relative py-5" style="background-color: #fffafc;">
    <div class="container">
      <h2 class="text-center mb-2 fw-bold section-title">Explore Categories</h2>
      <p class="text-center mb-5 lead">
        Discover our curated toy collections, crafted to spark creativity, learning, and fun for every age.
      </p>

      <div class="row g-4 position-relative category-wrapper">

        <!-- Featured Category -->
        <div class="col-lg-7 featured-wrapper">
          <div class="card h-100 text-center shadow category-card featured-card">
            <img src="../images/baby-playing.svg" class="card-img-top" alt="Early Development Toys">
            <div class="card-body">
              <h5 class="card-title display-6">Early Development Toys</h5>
              <p class="card-text">Fun and educational toys for toddlers to spark curiosity and learning.</p>
              <a href="#" class="btn btn-pastel btn-lg">Browse</a>
            </div>
          </div>
        </div>

        <!-- Smaller Categories -->
        <div class="col-lg-5 d-flex flex-column justify-content-between small-cards-wrapper">

          <div class="card h-50 text-center shadow category-card angled-card">
            <img src="../images/iron-man-figure.svg" class="card-img-top" alt="Action & Adventure Toys">
            <div class="card-body">
              <h5 class="card-title">Action & Adventure Toys</h5>
              <a href="#" class="btn btn-pastel">Browse</a>
            </div>
          </div>

          <div class="card h-50 text-center shadow category-card angled-card">
            <img src="../images/collectors.jpg" class="card-img-top" alt="Collector’s Vault">
            <div class="card-body">
              <h5 class="card-title">Collector’s Vault</h5>
              <a href="#" class="btn btn-pastel">Browse</a>
            </div>
          </div>

        </div>

      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer py-5 bg-pastel">
    <div class="container">
      <div class="row g-4 justify-content-center">

        <!-- Logo & About -->
        <div class="col-md-3 footer-card text-center text-md-start">
          <img src="../images/logo.png" alt="Toy Brigade Logo" class="footer-logo mb-2">
          <p class="small text-muted">Bringing joy and play to every child with toys made for fun and imagination.</p>
        </div>

        <!-- Quick Links -->
        <div class="col-md-3 footer-card text-center text-md-start">
          <h5 class="footer-title">Quick Links</h5>
          <ul class="list-unstyled">
            <li><a href="#" class="footer-link">Home</a></li>
            <li><a href="#" class="footer-link">Shop</a></li>
            <li><a href="#" class="footer-link">Categories</a></li>
            <li><a href="#" class="footer-link">Contact</a></li>
          </ul>
        </div>

        <!-- Categories -->
        <div class="col-md-3 footer-card text-center text-md-start">
          <h5 class="footer-title">Categories</h5>
          <ul class="list-unstyled">
            <li><a href="#" class="footer-link">Action Figures</a></li>
            <li><a href="#" class="footer-link">Board Games</a></li>
            <li><a href="#" class="footer-link">Educational Toys</a></li>
            <li><a href="#" class="footer-link">Stuffed Animals</a></li>
          </ul>
        </div>

        <!-- Contact -->
        <div class="col-md-3 footer-card text-center text-md-start">
          <h5 class="footer-title">Contact Us</h5>
          <p class="small mb-1">📍 123 Play Street, Fun City</p>
          <p class="small mb-1"><i class="fas fa-envelope me-1"></i>hello@toybrigade.com</p>
          <p class="small mb-3"><i class="fas fa-phone me-1"></i>+123 456 7890</p>
          <div class="social-icons">
            <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
          </div>
        </div>

      </div>

      <div class="text-center py-3 mt-4 small border-top">
        © 2025 Toy Brigade. All rights reserved.
      </div>
    </div>
  </footer>


<?php
$page_scripts = []; // e.g., ['../js/home.js?v=1'] if you have page JS
include __DIR__ . '/partials/footer.php'; // loads bootstrap bundle + nav.js, closes </body></html>
?>
    
    <script>
      // CODE FOR THE REDIRECT CART
      function redirectCart() {
          // Check if the user is logged in
          if(!"<?php echo isset($_SESSION["username"]) ? $_SESSION["username"] : '' ?>") {
              // Redirect the user to the login page
              alert("You are not logged in. Please log into your account and try again.");
          }
      }
  </script> 
  <script src="../js/main.js"></script>
</body>

</html>

  <?php
//   echo '<script>';
// if(!isset($_SESSION['user_email'])) {
//     // Redirect the user to the login page
//     echo "alert('You are not logged in. Please log into your account and try again.')";
// }
// else{
//   echo'href="./shop.php"';
// }
// echo '</script>';
  ?>