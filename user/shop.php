<?php
session_start();
include 'connection.php';

// -----------------------------
// Inputs
// -----------------------------
$search   = isset($_GET['search']) ? trim($_GET['search']) : '';
$page     = isset($_GET['page'])   ? max(1, (int)$_GET['page']) : 1;
$sort     = isset($_GET['sort'])   ? $_GET['sort'] : 'newest';

$per_page = 8;
$offset   = ($page - 1) * $per_page;

// -----------------------------
// Sort whitelist  → SQL ORDER BY
// -----------------------------
switch ($sort) {
  case 'price-asc':  $order_by = 'price ASC'; break;
  case 'price-desc': $order_by = 'price DESC'; break;
  case 'name-asc':   $order_by = 'productName ASC'; break;
  case 'name-desc':  $order_by = 'productName DESC'; break;
  case 'newest':
  default:           $order_by = 'productID DESC'; break;
}

// -----------------------------
// Add to Cart (prepared)
// -----------------------------
if (isset($_POST['add_to_cart']) && isset($_SESSION['email'])) {
  $customerID = (int)$_SESSION['customerID'];
  $productID  = (int)($_POST['productID'] ?? 0);
  $quantity   = max(1, (int)($_POST['quantity'] ?? 1));

  // Check existing
  $chk = $conn->prepare("SELECT quantity FROM cart WHERE customerID = ? AND productID = ?");
  $chk->bind_param("ii", $customerID, $productID);
  $chk->execute();
  $rs = $chk->get_result();
  if ($row = $rs->fetch_assoc()) {
    $chk->close();
    $upd = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE customerID = ? AND productID = ?");
    $upd->bind_param("iii", $quantity, $customerID, $productID);
    $upd->execute();
    $upd->close();
  } else {
    $chk->close();
    $ins = $conn->prepare("INSERT INTO cart (customerID, productID, quantity) VALUES (?, ?, ?)");
    $ins->bind_param("iii", $customerID, $productID, $quantity);
    $ins->execute();
    $ins->close();
  }

  // PRG pattern (avoid resubmits)
  $q = http_build_query([
    'added'  => $productID,
    'search' => $search,
    'sort'   => $sort,
    'page'   => $page
  ]);
  header("Location: shop.php?$q");
  exit();
}

// -----------------------------
// Fetch products (prepared)
// -----------------------------
$products = [];
$total_products = 0;

if ($search !== '') {
  // Count
  $cnt = $conn->prepare("
    SELECT COUNT(*) AS c
    FROM products
    WHERE productName LIKE CONCAT('%', ?, '%')
       OR productDesc LIKE CONCAT('%', ?, '%')
  ");
  $cnt->bind_param("ss", $search, $search);
  $cnt->execute();
  $cnt_res = $cnt->get_result();
  if ($r = $cnt_res->fetch_assoc()) $total_products = (int)$r['c'];
  $cnt->close();

  // Page
  $sql = "
    SELECT productID, productName, price, stock, productDesc, prodImage, status
    FROM products
    WHERE productName LIKE CONCAT('%', ?, '%')
       OR productDesc LIKE CONCAT('%', ?, '%')
    ORDER BY $order_by
    LIMIT ? OFFSET ?
  ";
  $stm = $conn->prepare($sql);
  $stm->bind_param("ssii", $search, $search, $per_page, $offset);
  $stm->execute();
  $res = $stm->get_result();
  while ($row = $res->fetch_assoc()) $products[] = $row;
  $stm->close();

} else {
  // Count
  $cnt = $conn->query("SELECT COUNT(*) AS c FROM products");
  if ($cnt && ($r = $cnt->fetch_assoc())) $total_products = (int)$r['c'];

  // Page
  $sql = "
    SELECT productID, productName, price, stock, productDesc, prodImage, status
    FROM products
    ORDER BY $order_by
    LIMIT ? OFFSET ?
  ";
  $stm = $conn->prepare($sql);
  $stm->bind_param("ii", $per_page, $offset);
  $stm->execute();
  $res = $stm->get_result();
  while ($row = $res->fetch_assoc()) $products[] = $row;
  $stm->close();
}

$total_pages = max(1, (int)ceil($total_products / $per_page));

/* --------------------------------------------------
   Shared header + navbar
-------------------------------------------------- */
$page_title = "Toy Brigade | Shop";
$page_css   = ['../css/shop.css?v=1'];
include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/navbar.php';
?>

<!-- Hero (pastel background via CSS; removed broken <img>) -->
<section class="shop-hero d-flex align-items-center justify-content-center text-center">
  <div class="hero-text">
    <h1 class="display-4 splice-text">Discover Our Toys</h1>
    <p class="lead">Fun, playful, and full of imagination for every child</p>
    <a href="#all-products" class="btn btn-pastel btn-lg mt-3">Shop Now</a>
  </div>
</section>

<!-- All Products -->
<section id="all-products" class="section-gap">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="section-title splice-text">All Products</h2>
      <p class="text-muted mb-0">
        <span id="tb-count">
          Showing <?php echo min($per_page * $page, $total_products); ?> of <?php echo $total_products; ?> results
        </span>
      </p>
    </div>

    <div class="tb-toolbar row g-2 align-items-center mb-3">
      <!-- Search (client-side filter; prefilled with server search) -->
      <div class="col-12 col-md-5">
        <div class="input-group pastel-input-group">
          <span class="input-group-text"><i class="fas fa-search"></i></span>
          <input
            id="tb-search"
            type="search"
            class="form-control pastel-input"
            placeholder="product name, tag, description"
            value="<?php echo htmlspecialchars($search); ?>"
          />
        </div>
      </div>

      <div class="col-6 col-md-3">
        <select id="tb-category" class="form-select pastel-input">
          <option>All</option>
        </select>
      </div>

      <!-- Sort (preserve search) -->
      <div class="col-6 col-md-2">
        <form method="GET" action="" id="sort-form">
          <input type="hidden" name="page" value="1">
          <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
          <select id="tb-sort" name="sort" class="form-select pastel-input sort-select" onchange="document.getElementById('sort-form').submit()">
            <option value="newest"     <?php echo $sort == 'newest'     ? 'selected' : ''; ?>>Newest</option>
            <option value="price-asc"  <?php echo $sort == 'price-asc'  ? 'selected' : ''; ?>>Price ↑</option>
            <option value="price-desc" <?php echo $sort == 'price-desc' ? 'selected' : ''; ?>>Price ↓</option>
            <option value="name-asc"   <?php echo $sort == 'name-asc'   ? 'selected' : ''; ?>>Name A→Z</option>
            <option value="name-desc"  <?php echo $sort == 'name-desc'  ? 'selected' : ''; ?>>Name Z→A</option>
          </select>
        </form>
      </div>

      <div class="col-12 col-md-2">
        <div class="input-group pastel-input-group">
          <span class="input-group-text">₱</span>
          <input id="tb-price-min" type="number" class="form-control pastel-input" placeholder="Min" />
          <input id="tb-price-max" type="number" class="form-control pastel-input" placeholder="Max" />
        </div>
      </div>
    </div>

    <!-- Success message -->
    <?php if (isset($_GET['added'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        Product added to cart successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <!-- Grid -->
    <div class="row g-3" id="tb-grid" aria-live="polite">
      <?php if (!empty($products)): ?>
        <?php foreach ($products as $row): ?>
          <?php
            // stock class/text
            if ((int)$row['stock'] > 20) { $stock_class = 'in-stock'; $stock_text = 'In Stock'; }
            elseif ((int)$row['stock'] > 0) { $stock_class = 'low-stock'; $stock_text = 'Low Stock'; }
            else { $stock_class = 'out-of-stock'; $stock_text = 'Out of Stock'; }

            // image path (keep your current location)
            $img = '../uploads/' . ltrim((string)$row['prodImage'], '/');
          ?>
          <div class="col-md-4 col-lg-3">
            <div class="product-card card h-100">
              <img src="<?php echo htmlspecialchars($img); ?>" class="product-image card-img-top" alt="<?php echo htmlspecialchars($row['productName']); ?>">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?php echo htmlspecialchars($row['productName']); ?></h5>
                <p class="price-tag">₱<?php echo number_format((float)$row['price'], 2); ?></p>
                <p class="stock-info <?php echo $stock_class; ?>"><?php echo $stock_text; ?> (<?php echo (int)$row['stock']; ?>)</p>
                <p class="card-text flex-grow-1"><?php echo htmlspecialchars(mb_strimwidth($row['productDesc'], 0, 100, '…', 'UTF-8')); ?></p>
                <div class="d-flex justify-content-between mt-auto">
                  <?php if ((int)$row['stock'] > 0): ?>
                    <form method="POST" action="" class="d-inline-flex flex-grow-1 me-2">
                      <input type="hidden" name="productID" value="<?php echo (int)$row['productID']; ?>">
                      <input type="hidden" name="quantity" value="1">
                      <button type="submit" name="add_to_cart" class="btn btn-pastel w-100">Add to Cart</button>
                    </form>
                  <?php else: ?>
                    <button class="btn btn-outline-secondary flex-grow-1 me-2" disabled>Out of Stock</button>
                  <?php endif; ?>

                  <button class="btn btn-outline-pastel wishlist-btn" data-product-id="<?php echo (int)$row['productID']; ?>" style="width: 45px; flex-shrink: 0;">
                    <i class="fas fa-heart"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center"><p>No products found.</p></div>
      <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
      <nav aria-label="Product pagination" class="mt-5">
        <ul class="pagination justify-content-center">
          <?php if ($page > 1): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?php echo $page - 1; ?>&sort=<?php echo urlencode($sort); ?>&search=<?php echo urlencode($search); ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
          <?php endif; ?>

          <?php
            $start_page = max(1, $page - 2);
            $end_page   = min($total_pages, $start_page + 4);
            for ($i = $start_page; $i <= $end_page; $i++):
          ?>
            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
              <a class="page-link" href="?page=<?php echo $i; ?>&sort=<?php echo urlencode($sort); ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>

          <?php if ($page < $total_pages): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?php echo $page + 1; ?>&sort=<?php echo urlencode($sort); ?>&search=<?php echo urlencode($search); ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    <?php endif; ?>

  </div>
</section>

<!-- Page footer content (your pretty footer UI) -->
<footer class="footer py-5 bg-pastel">
  <div class="container">
    <div class="row g-4 justify-content-center">

      <div class="col-md-3 footer-card text-center text-md-start">
        <img src="../images/logo.png" alt="Toy Brigade Logo" class="footer-logo mb-2">
        <p class="small text-muted">Bringing joy and play to every child with toys made for fun and imagination.</p>
      </div>

      <div class="col-md-3 footer-card text-center text-md-start">
        <h5 class="footer-title">Quick Links</h5>
        <ul class="list-unstyled">
          <li><a href="index.php" class="footer-link">Home</a></li>
          <li><a href="shop.php" class="footer-link">Shop</a></li>
          <li><a href="shop.php#all-products" class="footer-link">Categories</a></li>
          <li><a href="contact.php" class="footer-link">Contact</a></li>
        </ul>
      </div>

      <div class="col-md-3 footer-card text-center text-md-start">
        <h5 class="footer-title">Categories</h5>
        <ul class="list-unstyled">
          <li><a href="#" class="footer-link">Action Figures</a></li>
          <li><a href="#" class="footer-link">Board Games</a></li>
          <li><a href="#" class="footer-link">Educational Toys</a></li>
          <li><a href="#" class="footer-link">Stuffed Animals</a></li>
        </ul>
      </div>

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

<!-- In-page JS (keep lightweight). Bootstrap bundle loads in partials/footer.php -->
<script>
  // Simple client-side search filter (on current page only)
  document.getElementById('tb-search')?.addEventListener('keyup', function() {
    const q = this.value.toLowerCase();
    const cards = document.querySelectorAll('#tb-grid .product-card');
    let visible = 0;
    cards.forEach(card => {
      const title = card.querySelector('.card-title')?.textContent.toLowerCase() || '';
      const desc  = card.querySelector('.card-text')?.textContent.toLowerCase() || '';
      const show = title.includes(q) || desc.includes(q);
      card.parentElement.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    document.getElementById('tb-count').textContent =
      'Showing ' + visible + ' of <?php echo $total_products; ?> results';
  });

  // Wishlist toggle (UI only for now)
  document.querySelectorAll('.wishlist-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      this.classList.toggle('active');
      const id = this.getAttribute('data-product-id');
      // TODO: AJAX to persist
      console.log(this.classList.contains('active') ? 'Add ' + id : 'Remove ' + id);
    });
  });
</script>

<?php
// Shared footer (Bootstrap bundle + nav.js + close tags)
$page_scripts = []; // e.g., ['../js/shop.js?v=1'] if you externalize the inline JS
include __DIR__ . '/partials/footer.php';
