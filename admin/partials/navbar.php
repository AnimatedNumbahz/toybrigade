<?php
// user/partials/navbar.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($conn)) { @include_once __DIR__ . '/../connection.php'; }

// Cart count (compute if page didn’t set it)
if (!isset($cart_count)) {
  $cart_count = 0;
  if (!empty($_SESSION['customerID']) && isset($conn) && $conn instanceof mysqli) {
    if ($stmt = $conn->prepare("SELECT COALESCE(SUM(quantity),0) AS total FROM cart WHERE customerID = ?")) {
      $stmt->bind_param("i", $_SESSION['customerID']);
      if ($stmt->execute()) {
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) $cart_count = (int)$row['total'];
      }
      $stmt->close();
    }
  }
}
?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-pastel shadow-sm sticky-top playful-nav">
  <div class="container">

    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="./shop.php">
      <img src="../images/logo2.png" alt="Toy Brigade Logo" class="logo">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
            aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto playful-nav">

        <li class="nav-item">
          <a class="nav-link" href="./index.php"><span class="me-1">🏠</span>Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="./shop.php"><span class="me-1">🛒</span>Shop</a>
        </li>

        <!-- Categories (top-level) -->
        <li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown"
   role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
            🧸 Categories
          </a>

          <!-- Mega dropdown -->
          <ul class="dropdown-menu p-3 mega-dropdown" aria-labelledby="categoriesDropdown">

            <!-- Main Category 1 -->
            <li class="dropdown-submenu">
              <a class="dropdown-item dropdown-toggle" href="category-earlydev.php">👶 Early Development Toys</a>
              <ul class="dropdown-menu">
                <li class="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" href="#">Sensory & Baby Play</a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="product-sensory-1.html">Fisher-Price Laugh & Learn Puppy</a></li>
                    <li><a class="dropdown-item" href="product-sensory-2.html">VTech Sit-to-Stand Walker</a></li>
                    <li><a class="dropdown-item" href="product-sensory-3.html">Bright Starts Tummy Time Mat</a></li>
                    <li><a class="dropdown-item" href="product-sensory-4.html">Infantino Multi Ball Set</a></li>
                    <li><a class="dropdown-item" href="product-sensory-5.html">LeapFrog My Pal Scout</a></li>
                  </ul>
                </li>

                <li class="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" href="#">STEM & Learning</a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="product-stem-1.html">LEGO Duplo Alphabet Truck</a></li>
                    <li><a class="dropdown-item" href="product-stem-2.html">Osmo Genius Starter Kit</a></li>
                    <li><a class="dropdown-item" href="product-stem-3.html">Melissa & Doug Counting Caterpillar</a></li>
                    <li><a class="dropdown-item" href="product-stem-4.html">LeapFrog LeapStart System</a></li>
                    <li><a class="dropdown-item" href="product-stem-5.html">Magna-Tiles Starter Set</a></li>
                  </ul>
                </li>

                <li class="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" href="#">Pretend Play & Roleplay</a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="product-roleplay-1.html">Wooden Kitchen Playset</a></li>
                    <li><a class="dropdown-item" href="product-roleplay-2.html">Barbie Doctor Playset</a></li>
                    <li><a class="dropdown-item" href="product-roleplay-3.html">Play-Doh Kitchen Creations</a></li>
                    <li><a class="dropdown-item" href="product-roleplay-4.html">Fisher-Price Cash Register</a></li>
                    <li><a class="dropdown-item" href="product-roleplay-5.html">Little Tikes Washer-Dryer</a></li>
                  </ul>
                </li>
              </ul>
            </li>

            <!-- Main Category 2 -->
            <li class="dropdown-submenu">
              <a class="dropdown-item dropdown-toggle" href="category-action.php">⚔️ Action & Adventure Toys</a>
              <ul class="dropdown-menu">
                <li class="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" href="#">Action Figures & Superheroes</a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="product-action-1.html">Marvel Avengers Iron Man</a></li>
                    <li><a class="dropdown-item" href="product-action-2.html">DC Batman Figure</a></li>
                    <li><a class="dropdown-item" href="product-action-3.html">Transformers Optimus Prime</a></li>
                    <li><a class="dropdown-item" href="product-action-4.html">Spider-Man Web Launcher</a></li>
                    <li><a class="dropdown-item" href="product-action-5.html">Power Rangers Red Ranger</a></li>
                  </ul>
                </li>
                <li class="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" href="#">Vehicles & Playsets</a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="product-vehicle-1.html">Hot Wheels Super Track</a></li>
                    <li><a class="dropdown-item" href="product-vehicle-2.html">LEGO City Police Station</a></li>
                    <li><a class="dropdown-item" href="product-vehicle-3.html">Paw Patrol Lookout Tower</a></li>
                    <li><a class="dropdown-item" href="product-vehicle-4.html">Tonka Steel Dump Truck</a></li>
                    <li><a class="dropdown-item" href="product-vehicle-5.html">Matchbox Rescue Helicopter</a></li>
                  </ul>
                </li>
                <li class="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" href="#">Outdoor & Active Toys</a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="product-outdoor-1.html">Nerf Elite Blaster</a></li>
                    <li><a class="dropdown-item" href="product-outdoor-2.html">Slip ‘N Slide Splash</a></li>
                    <li><a class="dropdown-item" href="product-outdoor-3.html">Razor A Kick Scooter</a></li>
                    <li><a class="dropdown-item" href="product-outdoor-4.html">Frisbee Ultimate Disc</a></li>
                    <li><a class="dropdown-item" href="product-outdoor-5.html">Little Tikes Climber</a></li>
                  </ul>
                </li>
              </ul>
            </li>

            <!-- Main Category 3 -->
            <li class="dropdown-submenu">
              <a class="dropdown-item dropdown-toggle" href="category-collectors.php">🎴 Collector's Vault</a>
              <ul class="dropdown-menu">
                <li class="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" href="#">Anime & Pop Culture</a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="product-anime-1.html">Funko Pop! Naruto</a></li>
                    <li><a class="dropdown-item" href="product-anime-2.html">Dragon Ball Z Action Figure</a></li>
                    <li><a class="dropdown-item" href="product-anime-3.html">One Piece Luffy Figure</a></li>
                    <li><a class="dropdown-item" href="product-anime-4.html">My Hero Academia Deku</a></li>
                    <li><a class="dropdown-item" href="product-anime-5.html">Sailor Moon Wand</a></li>
                  </ul>
                </li>
                <li class="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" href="#">Retro & Nostalgia</a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="product-retro-1.html">Tamagotchi Original</a></li>
                    <li><a class="dropdown-item" href="product-retro-2.html">Polly Pocket Compact</a></li>
                    <li><a class="dropdown-item" href="product-retro-3.html">Game Boy Color</a></li>
                    <li><a class="dropdown-item" href="product-retro-4.html">Rubik’s Cube</a></li>
                    <li><a class="dropdown-item" href="product-retro-5.html">Beanie Babies Collection</a></li>
                  </ul>
                </li>
                <li class="dropdown-submenu">
                  <a class="dropdown-item dropdown-toggle" href="#">Filipino Exclusives</a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="product-filipino-1.html">Daruma Doll PH Edition</a></li>
                    <li><a class="dropdown-item" href="product-filipino-2.html">Jollibee Funko Pop</a></li>
                    <li><a class="dropdown-item" href="product-filipino-3.html">Voltes V Figure</a></li>
                    <li><a class="dropdown-item" href="product-filipino-4.html">Manila Jeepney Die-Cast</a></li>
                    <li><a class="dropdown-item" href="product-filipino-5.html">Sarimanok Collector Statue</a></li>
                  </ul>
                </li>
              </ul>
            </li>

          </ul> <!-- /mega-dropdown -->
        </li> <!-- /Categories -->

        <li class="nav-item">
          <a class="nav-link" href="./contact.php"><i class="fas fa-phone me-1"></i>Contact</a>
        </li>

        <?php if(isset($_SESSION['email'])): ?>
          <!-- User Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
               data-bs-toggle="dropdown" aria-expanded="false">
              👤 <?= htmlspecialchars($_SESSION['fname'] . ' ' . $_SESSION['lname']) ?>
            </a>
            <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 200px;" id="userDropdownMenu">
              <a class="dropdown-item" href="edit_profile.php"><i class="fas fa-user-edit me-2"></i>Edit Profile</a>
              <a class="dropdown-item" href="wishlist.php"><i class="fas fa-heart me-2"></i>Wishlist</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
            </div>
          </li>

          <!-- Cart -->
          <li class="nav-item d-flex align-items-center ms-2">
            <a href="cart.php" class="nav-link position-relative tb-cart-link" aria-label="Cart">
              <i class="fas fa-shopping-cart fa-lg tb-cart-icon"></i>
              <?php if ($cart_count > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle cart-count-pill">
                  <?= $cart_count ?>
                </span>
              <?php endif; ?>
            </a>
          </li>
        <?php else: ?>
          <!-- Login/Signup -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button"
               data-bs-toggle="dropdown" aria-expanded="false">
              👤 Login / Signup
            </a>
            <div class="dropdown-menu dropdown-menu-end p-4" style="min-width: 320px; overflow: hidden;"
                 id="accountDropdownMenu">
              <div class="form-slider d-flex" style="width:200%; transition: transform 0.4s ease;">
                <div class="form-panel" style="width:50%;">
                  <h6 class="dropdown-header">Login to your account</h6>
                  <form id="loginForm" action="login.php" method="POST">
                    <div class="mb-3"><input type="email" class="form-control pastel-input" name="email" placeholder="Email" required></div>
                    <div class="mb-3"><input type="password" class="form-control pastel-input" name="password" placeholder="Password" required></div>
                    <button type="submit" class="btn btn-pastel w-100" id="loginBtn" name="loginBtn">Login</button>
                    <div class="mt-2 text-center">
                      <small>New customer? <a href="#" id="showSignup">Create your account</a></small><br>
                      <small>Lost password? <a href="#">Recover password</a></small>
                    </div>
                  </form>
                </div>
                <div class="form-panel" style="width:50%;">
                  <h6 class="dropdown-header">Create my account</h6>
                  <form id="signupForm" action="register.php" method="POST">
                    <div class="mb-2"><input type="text" class="form-control pastel-input" name="fname" placeholder="First name" required></div>
                    <div class="mb-2"><input type="text" class="form-control pastel-input" name="lname" placeholder="Last name" required></div>
                    <div class="mb-2"><input type="email" class="form-control pastel-input" name="email" placeholder="Email" required></div>
                    <div class="mb-2"><input type="text" class="form-control pastel-input" name="lytcard" placeholder="Loyalty card number (optional)"></div>
                    <div class="mb-2"><input type="password" class="form-control pastel-input" name="password" placeholder="Password" required></div>
                    <button type="submit" class="btn btn-pastel w-100" id="signupBtn" name="signupBtn">Create account</button>
                    <div class="mt-2 text-center">
                      <small>Already have an account? <a href="#" id="showLogin">Login here</a></small>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>
