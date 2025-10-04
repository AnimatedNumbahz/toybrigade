<?php
include "connection.php";
include "auth_session.php";

// Check if items were selected
if (!isset($_POST['selected_items']) || empty($_POST['selected_items'])) {
    header("Location: cart.php");
    exit();
}

$selected_items = $_POST['selected_items'];
$placeholders = implode(',', array_fill(0, count($selected_items), '?'));
$types = str_repeat('i', count($selected_items));

// Get selected cart items for the logged-in user
$stmt = $conn->prepare("SELECT p.productID, p.productName, p.price, p.prodImage, c.quantity 
                        FROM cart c 
                        JOIN products p ON c.productID = p.productID 
                        WHERE c.customerID = ? AND p.productID IN ($placeholders)");
$params = array_merge([$_SESSION['customerID']], $selected_items);
$stmt->bind_param(str_repeat('i', count($params)), ...$params);
$stmt->execute();
$cart_result = $stmt->get_result();
$cart_items = $cart_result ? mysqli_fetch_all($cart_result, MYSQLI_ASSOC) : [];
$cart_count = count($cart_items);
$subtotal = 0;

// Calculate subtotal
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$customer_query = $conn->prepare("SELECT * FROM customer WHERE customerID = ?");
$customer_query->bind_param("i", $_SESSION['customerID']);
$customer_query->execute();
$customer_result = $customer_query->get_result();
$customer = $customer_result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $shippingMethod = $_POST['shippingMethod'] ?? '';

    // Calculate shipping cost
    $shippingCost = 0;
    switch ($shippingMethod) {
        case 'standard':
            $shippingCost = 150;
            break;
        case 'express':
            $shippingCost = 350;
            break;
        case 'pickup':
            $shippingCost = 0;
            break;
    }

    $totalAmount = $subtotal + $shippingCost;

    // Store order information in session WITHOUT creating database record yet
    $_SESSION['current_order'] = [
        'items' => $cart_items,
        'subtotal' => $subtotal,
        'shipping_cost' => $shippingCost,
        'total' => $totalAmount,
        'shipping_method' => $shippingMethod,
        'customer_info' => [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'address' => $address
        ]
    ];

    // Redirect to payment.php
    header('Location: payment.php');
    exit();
} else {
    $error = "Failed to place order. Please try again.";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Toy Brigade | Checkout</title>
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
    /* page sugar */
    .co-hero { background:#fff5f8; }
    .co-card { border:0; border-radius:18px; box-shadow:0 6px 16px rgba(255,182,193,.18); }
    .co-step { display:flex; gap:.5rem; align-items:center; }
    .co-step .bubble { width:28px; height:28px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-weight:700; }
    .bubble-active { background:#ffd24d; }
    .bubble-done { background:#c6f6d5; }
    .bubble-next { background:#eee; }
    .co-small { font-size:.92rem; }
    .co-field { border-radius:14px; border:2px solid rgba(255,182,193,.35); }
    .co-field:focus { border-color: rgba(255,182,193,.8); box-shadow:0 0 0 .2rem rgba(255,182,193,.25); }
    .co-summary { border:0; border-radius:18px; box-shadow:0 6px 16px rgba(255,182,193,.18); }
    .co-divider { border-top:1px dashed rgba(0,0,0,.08); }
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

  <!-- Hero -->
  <section class="co-hero py-4">
    <div class="container">
      <div class="d-flex align-items-center justify-content-between">
        <h1 class="section-title splice-text mb-0">Checkout</h1>
      </div>
    </div>
  </section>

  <!-- Content -->
  <main class="container my-4">
    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="row g-4">
      <!-- Left: steps -->
      <div class="col-12 col-lg-8">
        <div class="card co-card p-3">
          <!-- Step 1: Shipping -->
          <section id="step-1">
            <h5 class="mb-3">Shipping Details</h5>
            <form method="POST" novalidate>
              <!-- Hidden fields to pass selected items -->
              <?php foreach ($selected_items as $item_id): ?>
                <input type="hidden" name="selected_items[]" value="<?php echo $item_id; ?>">
              <?php endforeach; ?>
              
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">First Name</label>
                  <input type="text" class="form-control co-field" name="firstName" value="<?php echo $customer['fname']; ?>" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Last Name</label>
                  <input type="text" class="form-control co-field" name="lastName" value="<?php echo $customer['lname']; ?>" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Email</label>
                  <input type="email" class="form-control co-field" name="email" value="<?php echo $customer['email']; ?>" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Phone</label>
                  <input type="tel" class="form-control co-field" name="phone" placeholder="Phone Number" required>
                </div>
                <div class="col-12">
                  <label class="form-label">Address</label>
                  <textarea class="form-control co-field" name="address" rows="3" placeholder="Enter your complete shipping address" required></textarea>
                </div>
                <div class="col-12">
                  <label class="form-label">Shipping Method</label>
                  <select class="form-select co-field" name="shippingMethod" required>
                    <option value="standard">Standard Shipping (₱150)</option>
                    <option value="express">Express Shipping (₱350)</option>
                    <option value="pickup">Store Pickup (Free)</option>
                  </select>
                </div>
                <div class="col-12">
                  <button type="submit" class="btn btn-pastel" name="place_order">Place Order & Continue to Payment</button>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>

      <!-- Right: summary -->
      <div class="col-12 col-lg-4">
        <div class="card co-summary p-3">
          <h5 class="mb-3">Order Summary</h5>
          <div id="sum-list" class="mb-2">
              <?php foreach ($cart_items as $item): ?>
                  <div class="d-flex justify-content-between small mb-1" data-product-id="<?php echo $item['productID']; ?>">
                      <span><?php echo $item['productName']; ?> (x<?php echo $item['quantity']; ?>)</span>
                      <span>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                  </div>
              <?php endforeach; ?>
          </div>
          <div class="d-flex justify-content-between"><span>Items Subtotal</span><span id="co-sub">₱<?php echo number_format($subtotal, 2); ?></span></div>
          <div class="d-flex justify-content-between"><span>Shipping</span><span id="co-ship">—</span></div>
          <div class="co-divider my-2"></div>
          <div class="d-flex justify-content-between"><strong>Total</strong><strong id="co-total">₱<?php echo number_format($subtotal, 2); ?></strong></div>
          <div class="small text-muted mt-2">Sandbox checkout — no real charges.</div>
        </div>
      </div>
    </div>
  </main>

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

  <script src="../js/checkout.js"></script>
  <script src="../js/main.js"></script>
</body>
</html>