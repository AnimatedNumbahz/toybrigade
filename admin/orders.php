<?php
// DEV (remove in prod)
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'connection.php';
include 'auth_session.php';

$customerID = $_SESSION['customerID'] ?? null;
if (!$customerID) {
  header("Location: login.php");
  exit();
}

/* ------------------------------
   Pagination (simple)
------------------------------ */
$per_page = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $per_page;

/* Count total orders for this user */
$total_orders = 0;
$cnt = $conn->prepare("SELECT COUNT(*) AS c FROM `orders` WHERE customerID = ?");
$cnt->bind_param("i", $customerID);
$cnt->execute();
$cnt_res = $cnt->get_result();
if ($r = $cnt_res->fetch_assoc()) $total_orders = (int)$r['c'];
$cnt->close();

/* Fetch orders for this page */
$orders = [];
$ord = $conn->prepare("
  SELECT orderID, total, status, COALESCE(created_at, NOW()) AS created_at
  FROM `orders`
  WHERE customerID = ?
  ORDER BY orderID DESC
  LIMIT ? OFFSET ?
");
$ord->bind_param("iii", $customerID, $per_page, $offset);
$ord->execute();
$ord_res = $ord->get_result();
$order_ids = [];
while ($o = $ord_res->fetch_assoc()) {
  $o['orderID'] = (int)$o['orderID'];
  $orders[] = $o;
  $order_ids[] = $o['orderID'];
}
$ord->close();

/* Fetch items for orders in one go */
$items_by_order = [];
if (!empty($order_ids)) {
  $ph = implode(',', array_fill(0, count($order_ids), '?'));
  $types = str_repeat('i', count($order_ids));
  $sql = "
    SELECT oi.orderID, oi.productID, p.productName, p.prodImage, oi.quantity, oi.price, oi.lineTotal
    FROM `orderitems` oi
    JOIN `products` p ON p.productID = oi.productID
    WHERE oi.orderID IN ($ph)
    ORDER BY oi.orderID DESC, oi.productID ASC
  ";
  $stm = $conn->prepare($sql);
  $stm->bind_param($types, ...$order_ids);
  $stm->execute();
  $res = $stm->get_result();
  while ($row = $res->fetch_assoc()) {
    $oid = (int)$row['orderID'];
    $items_by_order[$oid][] = $row;
  }
  $stm->close();
}

/* Helpers */
function peso($n) { return '₱' . number_format((float)$n, 2); }
function status_class($s) {
  $key = strtolower(trim((string)$s));
  return match ($key) {
    'pending'    => 'badge status-badge status-pending',
    'processing' => 'badge status-badge status-processing',
    'shipped'    => 'badge status-badge status-shipped',
    'completed'  => 'badge status-badge status-completed',
    'cancelled'  => 'badge status-badge status-cancelled',
    default      => 'badge status-badge status-default'
  };
}

/* Shared header + navbar */
$page_title = "Toy Brigade | My Orders";
$page_css   = ['../css/orders.css']; // <-- add our status colors
include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/navbar.php';
?>

<main class="container my-5">
  <h2 class="text-center splice-text mb-4">My Orders</h2>

  <?php if (empty($orders)): ?>
    <div class="card p-4 text-center">
      <p class="mb-3">You don’t have any orders yet.</p>
      <a href="shop.php" class="btn btn-pastel">Start Shopping</a>
    </div>
  <?php else: ?>

    <?php foreach ($orders as $o): ?>
      <?php
        $oid    = (int)$o['orderID'];
        $total  = (float)$o['total'];
        $status = $o['status'];
        $date   = date('M d, Y h:i A', strtotime($o['created_at']));
        $cls    = status_class($status);
        $items  = $items_by_order[$oid] ?? [];
      ?>
      <div class="card rounded-4 mb-3">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
          <div class="mb-2">
            <div class="fw-bold">Order #<?php echo $oid; ?></div>
            <div class="text-muted small"><?php echo htmlspecialchars($date); ?></div>
          </div>

          <div class="mb-2">
            <span class="<?php echo $cls; ?>"><?php echo htmlspecialchars($status); ?></span>
          </div>

          <div class="mb-2">
            <div class="text-muted small">Total</div>
            <div class="fw-bold"><?php echo peso($total); ?></div>
          </div>

          <div class="mb-2">
            <button class="btn btn-outline-secondary btn-sm" type="button"
                    data-bs-toggle="collapse" data-bs-target="#items-<?php echo $oid; ?>"
                    aria-expanded="false" aria-controls="items-<?php echo $oid; ?>">
              View items
            </button>
          </div>
        </div>

        <div class="collapse" id="items-<?php echo $oid; ?>">
          <div class="border-top p-3">
            <?php if (empty($items)): ?>
              <div class="text-muted small">No items found for this order.</div>
            <?php else: ?>
              <div class="table-responsive">
                <table class="table align-middle">
                  <thead>
                    <tr>
                      <th style="width:60px;"></th>
                      <th>Product</th>
                      <th class="text-center">Qty</th>
                      <th class="text-end">Price</th>
                      <th class="text-end">Line Total</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php foreach ($items as $it): ?>
                    <?php
                      $imgRaw = trim((string)($it['prodImage'] ?? ''));
                      $img = "../images/placeholder.png";
                      if ($imgRaw !== '') {
                        if (preg_match('/^https?:\\/\\//', $imgRaw)) $img = $imgRaw;
                        elseif (strpos($imgRaw, 'images/') === 0 || strpos($imgRaw, 'uploads/') === 0) $img = '../' . $imgRaw;
                        else $img = '../images/' . ltrim($imgRaw, '/');
                      }
                    ?>
                    <tr>
                      <td>
                        <div style="width:48px;height:48px;overflow:hidden;border-radius:10px;background:#f8f9fa;">
                          <img src="<?php echo htmlspecialchars($img); ?>" alt=""
                               style="width:100%;height:100%;object-fit:cover;">
                        </div>
                      </td>
                      <td><?php echo htmlspecialchars($it['productName']); ?></td>
                      <td class="text-center"><?php echo (int)$it['quantity']; ?></td>
                      <td class="text-end"><?php echo peso($it['price']); ?></td>
                      <td class="text-end"><?php echo peso($it['lineTotal']); ?></td>
                    </tr>
                  <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <!-- Pagination -->
    <?php
      $total_pages = max(1, (int)ceil($total_orders / $per_page));
      if ($total_pages > 1):
    ?>
      <nav aria-label="Orders page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
          <?php $prev = max(1, $page - 1); $next = min($total_pages, $page + 1); ?>
          <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo $prev; ?>">Prev</a>
          </li>
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
              <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo $next; ?>">Next</a>
          </li>
        </ul>
      </nav>
    <?php endif; ?>

  <?php endif; ?>
</main>

<?php
// Shared footer (Bootstrap bundle + nav.js + close tags)
$page_scripts = [];
include __DIR__ . '/partials/footer.php';
