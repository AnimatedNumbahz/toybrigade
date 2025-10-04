<?php 

include "connection.php";
include "auth_session.php";

// Get cart items for the logged-in user
$cart_query = "SELECT p.productID, p.productName, p.price, p.prodImage, c.quantity 
               FROM cart c 
               JOIN products p ON c.productID = p.productID 
               WHERE c.customerID = {$_SESSION['customerID']}";
$cart_result = mysqli_query($conn, $cart_query);
$cart_items = $cart_result ? mysqli_fetch_all($cart_result, MYSQLI_ASSOC) : [];
$cart_count = count($cart_items);
$subtotal = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Toy Brigade | Cart</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap / Fonts / Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Baloo+2:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Theme CSS -->
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/footer.css" />
  <link rel="stylesheet" href="../css/navbar.css" />
  <link rel="stylesheet" href="../css/shop.css" />

  <style>
    /* Page-level sugar that leans on your theme, minimal inline */
    .cart-hero { background: #fff5f8; }
    .cart-card { border: 0; border-radius: 18px; box-shadow: 0 6px 16px rgba(255, 182, 193, 0.18); }
    .cart-img { width: 72px; height: 72px; object-fit: cover; border-radius: 12px; }
    .qty-input { width: 72px; border-radius: 12px; }
    .remove-link { color: #dc3545; text-decoration: none; }
    .remove-link:hover { text-decoration: underline; }
    .summary-card { border: 0; border-radius: 18px; box-shadow: 0 6px 16px rgba(255, 182, 193, 0.18); }
  </style>
</head>
<body>
  <!-- Navbar (kept from your theme) -->
  <nav class="navbar navbar-expand-lg navbar-light bg-pastel shadow-sm sticky-top playful-nav">
    <div class="container">
      <!-- Bigger Logo -->
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="../images/logo2.png" alt="Toy Brigade Logo" class="logo">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
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
          <!-- Categories Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              🧸 Categories
            </a>
            <ul class="dropdown-menu p-3 mega-dropdown" aria-labelledby="categoriesDropdown">

              <!-- Main Category 1 -->
              <li class="dropdown-submenu">
                <a class="dropdown-item dropdown-toggle" href="category-earlydev.php">👶 Early Development Toys</a>
                <ul class="dropdown-menu">

                  <!-- Subcategory 1 -->
                  <li class="dropdown-submenu">
                    <a class="dropdown-item dropdown-toggle" href="#">Sensory & Baby Play</a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="product-sensory-1.html">Fisher-Price Laugh & Learn Puppy</a>
                      </li>
                      <li><a class="dropdown-item" href="product-sensory-2.html">VTech Sit-to-Stand Walker</a></li>
                      <li><a class="dropdown-item" href="product-sensory-3.html">Bright Starts Tummy Time Mat</a></li>
                      <li><a class="dropdown-item" href="product-sensory-4.html">Infantino Multi Ball Set</a></li>
                      <li><a class="dropdown-item" href="product-sensory-5.html">LeapF Frog My Pal Scout</a></li>
                    </ul>
                  </li>

                  <!-- Subcategory 2 -->
                  <li class="dropdown-submenu">
                    <a class="dropdown-item dropdown-toggle" href="#">STEM & Learning</a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="product-stem-1.html">LEGO Duplo Alphabet Truck</a></li>
                      <li><a class="dropdown-item" href="product-stem-2.html">Osmo Genius Starter Kit</a></li>
                      <li><a class="dropdown-item" href="product-stem-3.html">Melissa & Doug Counting Caterpillar</a>
                      </li>
                      <li><a class="dropdown-item" href="product-stem-4.html">LeapFrog LeapStart System</a></li>
                      <li><a class="dropdown-item" href="product-stem-5.html">Magna-Tiles Starter Set</a></li>
                    </ul>
                  </li>

                  <!-- Subcategory 3 -->
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

                  <!-- Subcategory 1 -->
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

                  <!-- Subcategory 2 -->
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

                  <!-- Subcategory 3 -->
                  <li class="dropdown-submenu">
                    <a class="dropdown-item dropdown-toggle" href="#">Outdoor & Active Toys</a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="product-outdoor-1.html">Nerf Elite Blaster</a></li>
                      <li><a class="dropdown-item" href="product-outdoor-2.html">Slip 'N Slide Splash</a></li>
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

                  <!-- Subcategory 1 -->
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

                  <!-- Subcategory 2 -->
                  <li class="dropdown-submenu">
                    <a class="dropdown-item dropdown-toggle" href="#">Retro & Nostalgia</a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="product-retro-1.html">Tamagotchi Original</a></li>
                      <li><a class="dropdown-item" href="product-retro-2.html">Polly Pocket Compact</a></li>
                      <li><a class="dropdown-item" href="product-retro-3.html">Game Boy Color</a></li>
                      <li><a class="dropdown-item" href="product-retro-4.html">Rubik's Cube</a></li>
                      <li><a class="dropdown-item" href="product-retro-5.html">Beanie Babies Collection</a></li>
                    </ul>
                  </li>

                  <!-- Subcategory 3 -->
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

            </ul>
          </li>

          </li>
          <li class="nav-item">
            <a class="nav-link" href="./contact.php"><i class="fas fa-phone me-1"></i>Contact</a>
          </li>
        


          <?php if(isset($_SESSION['email'])): ?>
          <!-- User Profile Dropdown (shown when logged in) -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              👤 <?php echo $_SESSION['fname'] . ' ' . $_SESSION['lname']; ?>
            </a>
            <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 200px;" id="userDropdownMenu">
              <a class="dropdown-item" href="#"><i class="fas fa-user-edit me-2"></i>Edit Profile</a>
              <a class="dropdown-item" href="#"><i class="fas fa-heart me-2"></i>Wishlist</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
            </div>
          </li>

        <?php else: ?>
        <!-- Login/Signup Dropdown (shown when not logged in) -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            👤 Login / Signup
          </a>
          <div class="dropdown-menu dropdown-menu-end p-4" style="min-width: 320px; overflow: hidden;"
            id="accountDropdownMenu">
            <!-- Sliding container -->
            <div class="form-slider d-flex" style="width:200%; transition: transform 0.4s ease;">
              <!-- Login Panel -->
              <div class="form-panel" style="width:50%;">
                <h6 class="dropdown-header">Login to your account</h6>
                <form id="loginForm" action="login.php" method="POST">  
                  <div class="mb-3">
                    <input type="email" class="form-control pastel-input" name="email" placeholder="Email" required>
                  </div>
                  <div class="mb-3">
                    <input type="password" class="form-control pastel-input" name="password" placeholder="Password" required>
                  </div>
                  <button type="submit" class="btn btn-pastel w-100" id="loginBtn" name="loginBtn">
                    <span class="default-text">Login</span>
                    <span class="loading-text d-none">Loading...</span>
                  </button>
                  <div class="mt-2 text-center">
                    <small>New customer? <a href="#" id="showSignup">Create your account</a></small><br>
                    <small>Lost password? <a href="#">Recover password</a></small>
                  </div>
                </form>
              </div>

              <!-- Signup Panel -->
              <div class="form-panel" style="width:50%;">
                <h6 class="dropdown-header">Create my account</h6>
                <form id="signupForm" action="signup.php" method="POST"> 
                  <div class="mb-2"><input type="text" class="form-control pastel-input" name="fname" placeholder="First name" 
                      required></div>
                  <div class="mb-2"><input type="text" class="form-control pastel-input" name="lname" placeholder="Last name" 
                      required></div>
                  <div class="mb-2"><input type="email" class="form-control pastel-input" name="email" placeholder="Email" required>
                  </div>
                  <div class="mb-2"><input type="text" class="form-control pastel-input" name="lytcard"
                      placeholder="Loyalty card number (optional)"></div>
                  <div class="mb-2"><input type="password" class="form-control pastel-input" name="password" placeholder="Password"
                      required></div>
                  <button type="submit" class="btn btn-pastel w-100" id="signupBtn" name="signupBtn">
                    <span class="default-text">Create account</span>
                    <span class="loading-text d-none">Creating...</span>
                  </button>
                  <div class="mt-2 text-center">
                    <small>Already have an account? <a href="#" id="showLogin">Login here</a></small>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </li>
        <?php endif; ?>
      </div>
    </div>
  </nav>


  <!-- Hero / Title -->
  <section class="cart-hero py-5">
    <div class="container text-center">
      <h1 class="section-title splice-text mb-2">Your Cart</h1>
    </div>
  </section>

   <!-- Cart Content -->
  <main class="container my-4">
    <!-- Main checkout form -->
    <form action="checkout.php" method="POST" id="cartForm">
      <div class="row g-4">
        <!-- Left: Items -->
        <div class="col-12 col-lg-8">
          <div class="card cart-card p-3">
            <div id="cart-empty" class="text-center py-5 <?php echo $cart_count === 0 ? '' : 'd-none'; ?>">
              <p class="mb-3">Your cart is empty.</p>
              <a href="shop.php" class="btn btn-pastel">Continue Shopping</a>
            </div>

            <?php if ($cart_count > 0): ?>
            <div class="form-check select-all">
              <input class="form-check-input" type="checkbox" id="selectAll">
              <label class="form-check-label" for="selectAll">
                Select all items for checkout
              </label>
            </div>
            <?php endif; ?>

            <div id="cart-list">
              <?php if ($cart_count > 0): ?>
                <?php foreach ($cart_items as $item): ?>
                  <?php 
                  $item_total = $item['price'] * $item['quantity'];
                  $subtotal += $item_total;
                  ?>
                  <div class="cart-item d-flex align-items-center border-bottom pb-3 mb-3">
                    <input type="checkbox" class="form-check-input item-checkbox" name="selected_items[]" 
                           value="<?php echo $item['productID']; ?>" data-price="<?php echo $item['price']; ?>" 
                           data-quantity="<?php echo $item['quantity']; ?>">
                    <img src="../uploads/<?php echo $item['prodImage']; ?>" alt="<?php echo $item['productName']; ?>" class="cart-img me-3">
                    <div class="flex-grow-1">
                      <h6 class="mb-1"><?php echo $item['productName']; ?></h6>
                      <p class="mb-1 price-tag">₱<?php echo number_format($item['price'], 2); ?></p>
                      <div class="d-flex align-items-center">
                        <!-- Update form - placed outside the main form -->
                        <div class="d-flex align-items-center me-3">
                          <label for="qty-<?php echo $item['productID']; ?>" class="me-2">Qty:</label>
                          <input type="number" id="qty-<?php echo $item['productID']; ?>" 
                                 value="<?php echo $item['quantity']; ?>" min="1" class="form-control form-control-sm qty-input"
                                 data-product-id="<?php echo $item['productID']; ?>">
                          <button type="button" class="btn btn-sm btn-outline-secondary ms-2 update-cart-btn" 
                                  data-product-id="<?php echo $item['productID']; ?>">Update</button>
                        </div>
                        <!-- Remove button -->
                        <button type="button" class="btn btn-sm remove-link delete-cart-btn" 
                                data-product-id="<?php echo $item['productID']; ?>">Remove</button>
                      </div>
                    </div>
                    <div class="text-end">
                      <p class="mb-0 fw-bold">₱<?php echo number_format($item_total, 2); ?></p>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>

        
        <div class="col-12 col-lg-4">
          <div class="card summary-card p-3">
            <h5 class="mb-3">Order Summary</h5>
            <div class="d-flex justify-content-between mb-2">
              <span>Items Subtotal</span>
              <span id="sum-subtotal">₱<?php echo number_format($subtotal, 2); ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Shipping</span>
              <span class="text-muted">Calculated at checkout</span>
            </div>
            <hr />
            <div class="d-flex justify-content-between mb-3">
              <strong>Total</strong>
              <strong id="sum-total">₱<?php echo number_format($subtotal, 2); ?></strong>
            </div>

            <?php if ($cart_count > 0): ?>
              <button type="submit" class="btn btn-pastel w-100" id="btn-checkout" name="proceed_checkout">
                Proceed to Checkout
              </button>
            <?php else: ?>
              <button type="button" class="btn btn-outline-secondary w-100" disabled>
                Proceed to Checkout
              </button>
            <?php endif; ?>

            <p class="small text-muted mt-2 mb-0">
              Note: Only selected items will be processed.
            </p>
          </div>
        </div>
      </div>
    </form>
  </main>


  <!-- You Might Also Like (Recommendations) -->
  <!-- <section class="container my-5">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h3 class="mb-0 splice-text">You might also like…</h3>
      <small class="text-muted">Based on what's in your cart</small>
    </div>
    <div class="row g-3" id="reco-cart">
      recommendations render here
      <?php
      // Simple recommendation logic - show some random products
      // $reco_query = "SELECT productID, productName, price, prodImage FROM products WHERE status = 1 ORDER BY RAND() LIMIT 4";
      // $reco_result = mysqli_query($conn, $reco_query);
      
      // if ($reco_result && mysqli_num_rows($reco_result) > 0) {
      //   while ($reco = mysqli_fetch_assoc($reco_result)) {
      //     echo '<div class="col-md-3">';
      //     echo '  <div class="card product-card h-100">';
      //     echo '    <img src="../images/products/' . $reco['prodImage'] . '" class="card-img-top" alt="' . $reco['productName'] . '" style="height: 150px; object-fit: cover;">';
      //     echo '    <div class="card-body">';
      //     echo '      <h6 class="card-title">' . $reco['productName'] . '</h6>';
      //     echo '      <p class="price-tag">₱' . number_format($reco['price'], 2) . '</p>';
      //     echo '      <a href="shop.php" class="btn btn-pastel btn-sm">View Product</a>';
      //     echo '    </div>';
      //     echo '  </div>';
      //     echo '</div>';
      //   }
      // }
      ?>
    </div>
  </section> -->

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
            <li><a href="index.php" class="footer-link">Home</a></li>
            <li><a href="shop.php" class="footer-link">Shop</a></li>
            <li><a href="#" class="footer-link">Categories</a></li>
            <li><a href="contact.php" class="footer-link">Contact</a></li>
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

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Select all functionality
    document.getElementById('selectAll')?.addEventListener('change', function() {
      const checkboxes = document.querySelectorAll('.item-checkbox');
      checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
      });
      updateCheckoutButton();
    });

    // Individual checkbox change
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
      checkbox.addEventListener('change', updateCheckoutButton);
    });

    // Update checkout button state
    function updateCheckoutButton() {
      const checkboxes = document.querySelectorAll('.item-checkbox');
      const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
      const checkoutBtn = document.getElementById('btn-checkout');
      
      if (anyChecked && <?php echo $cart_count > 0 ? 'true' : 'false'; ?>) {
        checkoutBtn.disabled = false;
        checkoutBtn.classList.remove('btn-outline-secondary');
        checkoutBtn.classList.add('btn-pastel');
      } else {
        checkoutBtn.disabled = true;
        checkoutBtn.classList.remove('btn-pastel');
        checkoutBtn.classList.add('btn-outline-secondary');
      }
    }

    // Form submission handler
    document.getElementById('cartForm')?.addEventListener('submit', function(e) {
      const checkboxes = document.querySelectorAll('.item-checkbox:checked');
      if (checkboxes.length === 0) {
        e.preventDefault();
        alert('Please select at least one item to proceed to checkout.');
        return false;
      }
      
      // Optional: Show loading state
      const checkoutBtn = document.getElementById('btn-checkout');
      if (checkoutBtn) {
        checkoutBtn.innerHTML = 'Processing...';
        checkoutBtn.disabled = true;
      }
      
      return true;
    });

    // Initialize button state
    updateCheckoutButton();

    // Update cart quantity functionality
    document.querySelectorAll('.update-cart-btn').forEach(button => {
      button.addEventListener('click', function() {
        const productID = this.getAttribute('data-product-id');
        const quantityInput = document.getElementById('qty-' + productID);
        const quantity = quantityInput.value;
        
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'update_cart.php';
        
        const productIDInput = document.createElement('input');
        productIDInput.type = 'hidden';
        productIDInput.name = 'productID';
        productIDInput.value = productID;
        
        const quantityInputField = document.createElement('input');
        quantityInputField.type = 'hidden';
        quantityInputField.name = 'quantity';
        quantityInputField.value = quantity;
        
        const updateCartInput = document.createElement('input');
        updateCartInput.type = 'hidden';
        updateCartInput.name = 'update_cart';
        updateCartInput.value = '1';
        
        form.appendChild(productIDInput);
        form.appendChild(quantityInputField);
        form.appendChild(updateCartInput);
        
        document.body.appendChild(form);
        form.submit();
      });
    });

    // Delete cart item functionality
    document.querySelectorAll('.delete-cart-btn').forEach(button => {
      button.addEventListener('click', function() {
        const productID = this.getAttribute('data-product-id');
        
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'delete_cart.php';
        
        const productIDInput = document.createElement('input');
        productIDInput.type = 'hidden';
        productIDInput.name = 'productID';
        productIDInput.value = productID;
        
        const deleteCartInput = document.createElement('input');
        deleteCartInput.type = 'hidden';
        deleteCartInput.name = 'delete_cart';
        deleteCartInput.value = '1';
        
        form.appendChild(productIDInput);
        form.appendChild(deleteCartInput);
        
        document.body.appendChild(form);
        form.submit();
      });
    });
  </script>
</body>
</html>