<?php
include 'connection.php';
include 'auth_session.php';

// Page meta + page-specific CSS
$page_title = "Toy Brigade | Wishlist";
$page_css   = ['../css/wishlist.css?v=1'];

// Shared head + open body
include __DIR__ . '/partials/header.php';

// Shared navbar (shows user name and cart count, requires Bootstrap bundle from footer)
include __DIR__ . '/partials/navbar.php';
?>

<!-- Hero -->
<section class="wish-hero py-4">
  <div class="container d-flex align-items-center justify-content-between">
    <h1 class="section-title splice-text mb-0">My Wishlist</h1>
    <div class="text-muted small">Saved items on this device</div>
  </div>
</section>

<!-- Content -->
<main class="container my-4">
  <div class="card wish-card p-3">
    <div id="wish-empty" class="text-center py-5 d-none">
      <p class="mb-3">Your wishlist is empty.</p>
      <a href="shop.php" class="btn btn-pastel">Browse Products</a>
    </div>

    <div class="row g-3" id="wish-grid">
      <!-- wishlist items render here -->
    </div>
  </div>
</main>

<!-- Footer (page content area) -->
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
          <li><a href="index.php" class="footer-link">Home</a></li>
          <li><a href="shop.php" class="footer-link">Shop</a></li>
          <li><a href="shop.php#categories" class="footer-link">Categories</a></li>
          <li><a href="contact.php" class="footer-link">Contact</a></li>
        </ul>
      </div>

      <!-- Categories -->
      <div class="col-md-3 footer-card text-center text-md-start">
        <h5 class="footer-title">Categories</h5>
        <ul class="list-unstyled">
          <li><a href="shop.php?cat=1" class="footer-link">Action Figures</a></li>
          <li><a href="shop.php?cat=2" class="footer-link">Board Games</a></li>
          <li><a href="shop.php?cat=3" class="footer-link">Educational Toys</a></li>
          <li><a href="shop.php?cat=4" class="footer-link">Stuffed Animals</a></li>
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
// Shared footer (loads Bootstrap bundle + nav.js and closes </body></html>)
$page_scripts = []; // e.g., ['../js/wishlist.js?v=1']
include __DIR__ . '/partials/footer.php';
