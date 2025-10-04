<?php
session_start();
include 'connection.php';

$page_title = "Toy Brigade | Contact Us";
$page_css   = ['../css/contact.css']; // optional, keep/remove as you like

include __DIR__ . '/partials/header.php';   // outputs <!DOCTYPE html> + <head> + opens <body>
include __DIR__ . '/partials/navbar.php';   // shared navbar (dropdowns need footer bundle)
?>

<body>

  <!-- Hero Section -->
  <section class="text-center py-5 bg-light">
    <h1 class="fw-bold">📬 Contact Us</h1>
    <p class="text-muted">We’d love to hear from you! Fill out the form or reach us directly.</p>

  </section>

  <!-- Contact Content -->
  <section class="container my-5">
    <div class="row g-4">

      <!-- Contact Form -->
      <div class="col-lg-6">
        <div class="card shadow p-4 rounded-4 contact-card">
          <h3 class="mb-3">Send us a message</h3>
          <form>
            <div class="mb-3">
              <label class="form-label">Your Name</label>
              <input type="text" class="form-control rounded-3" placeholder="Enter your name">
            </div>
            <div class="mb-3">
              <label class="form-label">Your Email</label>
              <input type="email" class="form-control rounded-3" placeholder="Enter your email">
            </div>
            <div class="mb-3">
              <label class="form-label">Subject</label>
              <input type="text" class="form-control rounded-3" placeholder="Subject">
            </div>
            <div class="mb-3">
              <label class="form-label">Message</label>
              <textarea class="form-control rounded-3" rows="5" placeholder="Type your message"></textarea>
            </div>
            <button type="submit" class="contact-btn">Send Message</button>

          </form>
        </div>
      </div>

      <!-- Contact Info -->
      <div class="col-lg-6 ">
        <div class="card shadow p-4 rounded-4 contact-card-2">
          <h3 class="mb-3">Get in Touch</h3>
          <p><strong>📍 Address:</strong> 123 Toy Street, Playtown, PH</p>
          <p><strong>📞 Phone:</strong> +63 912 345 6789</p>
          <p><strong>✉️ Email:</strong> support@siatoybrigade.com</p>
          <div class="mt-4">
            <iframe
              src="https://www.google.com/maps/place/Toy+Kingdom+-+SM+North+Edsa/@14.6568205,121.0278747,17z/data=!3m1!4b1!4m6!3m5!1s0x3397b6e2dcf2ade9:0xf4ee6827acaa43ae!8m2!3d14.6568153!4d121.0304496!16s%2Fg%2F1hhxq4_z3?entry=ttu&g_ep=EgoyMDI1MDgxOS4wIKXMDSoASAFQAw%3D%3D">
              width="100% height="250" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
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
// Add page-specific JS here if you have any (e.g., map, form JS)
$page_scripts = [ 'https://www.google.com/recaptcha/api.js']; // e.g., ['../js/contact.js?v=1']
include __DIR__ . '/partials/footer.php'; // Bootstrap bundle + nav.js + close body/html
?>
</body>

</html>