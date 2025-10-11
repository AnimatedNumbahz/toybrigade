<?php
include 'connection.php';
// include 'auth_session_admin.php';
// include 'admin_guard.php';

// if (empty($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'admin') {
//     http_response_code(403);
//     header('Location: AdminIndex.php');
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Toy Brigade | Inventory Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/Inventory.css"> 
  <link rel="stylesheet" href="../css/style.css"> 
  <style>
    :root {
      --primary-color: #8A7C6C;
    }
    .admin-navbar {
      background: linear-gradient(135deg, var(--primary-color), #6b5d4b);
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .admin-navbar .navbar-brand {
      color: white !important;
      font-weight: 600;
      font-size: 1.5rem;
    }
    .admin-navbar .nav-link {
      color: rgba(255,255,255,0.9) !important;
      font-weight: 500;
      padding: 0.5rem 1rem;
      border-radius: 0.375rem;
      transition: all 0.3s ease;
    }
    .admin-navbar .nav-link:hover,
    .admin-navbar .nav-link.active {
      background-color: rgba(255,255,255,0.15);
      color: white !important;
    }
    .btn-logout {
      background-color: rgba(255,255,255,0.1);
      color: white !important;
      border: 1px solid rgba(255,255,255,0.3);
      transition: all 0.3s ease;
    }
    .btn-logout:hover {
      background-color: rgba(255,255,255,0.2);
      transform: translateY(-1px);
    }
    .inventory-img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 6px;
    }
    .status-badge {
      padding: 5px 10px;
      border-radius: 5px;
      font-weight: 600;
      text-transform: capitalize;
    }
    .in-stock { background-color: #d1e7dd; color: #0f5132; }
    .low-stock { background-color: #fff3cd; color: #664d03; }
    .out-stock { background-color: #f8d7da; color: #842029; }
  </style>
</head>
<body>

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg admin-navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <img src="../images/logo2.png" alt="Toy Brigade Logo" style="width: 150px; height: auto;">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="AdminIndex.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="inventory.php">Products</a></li>
        <li class="nav-item"><a class="nav-link" href="customer.php">Customers</a></li>
      </ul>
      <a href="logout.php" class="btn btn-logout ms-lg-3">Logout</a>
    </div>
  </div>
</nav>

<div class="container py-4">

  <h1 class="inventory-title mb-4">Inventory Management</h1>

  <!-- Add Product Form -->
  <div class="card inventory-card mb-4">
    <div class="card-body">
      <h5 class="card-title">Add New Product</h5>
      <form method="POST" action="" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-md-3">
            <input type="text" name="pname" class="form-control" placeholder="Product Name" required>
          </div>
          <div class="col-md-3">
            <input type="text" name="subcategoryID" class="form-control" placeholder="Subcategory ID" required>
          </div>
          <div class="col-md-2">
            <input type="number" name="stock" class="form-control" placeholder="Stock" required>
          </div>
          <div class="col-md-2">
            <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required>
          </div>
          <div class="col-md-2">
            <input type="file" name="prodImage" class="form-control" accept="image/*">
          </div>
          <div class="col-md-12">
            <button type="submit" name="add" class="btn btn-success w-100">Add</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php
  // ADD product
  if (isset($_POST['add'])) {
      $pname = mysqli_real_escape_string($conn, $_POST['pname']);
      $subcategoryID = (int)$_POST['subcategoryID'];
      $stock = (int)$_POST['stock'];
      $price = (float)$_POST['price'];

      // Handle file upload
      $imgName = "";
      if (!empty($_FILES['prodImage']['name'])) {
          $targetDir = dirname(__DIR__) . '/uploads/';
          if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
          $imgName = time() . "_" . basename($_FILES["prodImage"]["name"]);
          $targetFile = $targetDir . $imgName;
          move_uploaded_file($_FILES["prodImage"]["tmp_name"], $targetFile);
      }

      // Auto status based on stock
      $status = ($stock == 0) ? 'out of stock' : (($stock < 10) ? 'low stock' : 'in stock');

      $query = "INSERT INTO products (productName, price, stock, productDesc, prodImage, status, subcategoryID) 
                VALUES ('$pname', $price, $stock, '', '$imgName', '$status', $subcategoryID)";
      mysqli_query($conn, $query);
      header("Location: inventory.php");
      exit;
  }

  // DELETE product
  if (isset($_GET['delete'])) {
      $id = (int)$_GET['delete'];
      mysqli_query($conn, "DELETE FROM products WHERE productID=$id");
      header("Location: inventory.php");
      exit;
  }

  // UPDATE product
  if (isset($_POST['update'])) {
      $id = (int)$_POST['id'];
      $pname = mysqli_real_escape_string($conn, $_POST['pname']);
      $subcategoryID = (int)$_POST['subcategoryID'];
      $stock = (int)$_POST['stock'];
      $price = (float)$_POST['price'];

      // Determine stock status
      $status = ($stock == 0) ? 'out of stock' : (($stock < 10) ? 'low stock' : 'in stock');

      // Handle updated image
      $imgSQL = "";
      if (!empty($_FILES['prodImage']['name'])) {
          $targetDir = dirname(__DIR__) . '/uploads/';
          if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
          $imgName = time() . "_" . basename($_FILES["prodImage"]["name"]);
          $targetFile = $targetDir . $imgName;
          move_uploaded_file($_FILES["prodImage"]["tmp_name"], $targetFile);
          $imgSQL = ", prodImage='$imgName'";
      }

      $query = "UPDATE products 
                SET productName='$pname', subcategoryID=$subcategoryID, stock=$stock, price=$price, status='$status' $imgSQL
                WHERE productID=$id";
      mysqli_query($conn, $query);
      header("Location: inventory.php");
      exit;
  }
  ?>

  <!-- Products Table -->
  <div class="table-responsive">
    <table class="table inventory-table align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Image</th>
          <th>Product Name</th>
          <th>Subcategory ID</th>
          <th>Stock</th>
          <th>Status</th>
          <th>Price</th>
          <th width="150">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM products");
        while ($row = mysqli_fetch_assoc($result)) {

            // Auto-update stock status dynamically
            $status = ($row['stock'] == 0) ? 'out of stock' : (($row['stock'] < 10) ? 'low stock' : 'in stock');
            if ($status != $row['status']) {
                mysqli_query($conn, "UPDATE products SET status='$status' WHERE productID={$row['productID']}");
                $row['status'] = $status;
            }

            // Badge style
            $badgeClass = ($status == 'in stock') ? 'in-stock' : (($status == 'low stock') ? 'low-stock' : 'out-stock');

            echo "<tr>";
            echo "<td>{$row['productID']}</td>";
            echo "<td>";
            if ($row['prodImage']) {
                echo "<img src='../uploads/{$row['prodImage']}' class='inventory-img'>";
            } else {
                echo "<span class='text-muted'>No image</span>";
            }
            echo "</td>";
            echo "<td>{$row['productName']}</td>";
            echo "<td>{$row['subcategoryID']}</td>";
            echo "<td>{$row['stock']}</td>";
            echo "<td><span class='status-badge $badgeClass'>{$status}</span></td>";
            echo "<td><span class='inventory-price'>₱" . number_format($row['price'], 2) . "</span></td>";
            echo "<td>
                    <button class='btn btn-sm btn-primary' data-bs-toggle='modal' data-bs-target='#editModal{$row['productID']}'>Edit</button>
                    <a href='inventory.php?delete={$row['productID']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Delete this product?')\">Delete</a>
                  </td>";
            echo "</tr>";

            // Edit Modal
            echo "
            <div class='modal fade' id='editModal{$row['productID']}' tabindex='-1'>
              <div class='modal-dialog'>
                <div class='modal-content'>
                  <form method='POST' action='' enctype='multipart/form-data'>
                    <div class='modal-header'>
                      <h5 class='modal-title'>Edit Product</h5>
                      <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                    </div>
                    <div class='modal-body'>
                      <input type='hidden' name='id' value='{$row['productID']}'>
                      <div class='mb-3'>
                        <label class='form-label'>Product Name</label>
                        <input type='text' name='pname' class='form-control' value='{$row['productName']}' required>
                      </div>
                      <div class='mb-3'>
                        <label class='form-label'>Subcategory ID</label>
                        <input type='text' name='subcategoryID' class='form-control' value='{$row['subcategoryID']}' required>
                      </div>
                      <div class='mb-3'>
                        <label class='form-label'>Stock</label>
                        <input type='number' name='stock' class='form-control' value='{$row['stock']}' required>
                      </div>
                      <div class='mb-3'>
                        <label class='form-label'>Price</label>
                        <input type='number' step='0.01' name='price' class='form-control' value='{$row['price']}' required>
                      </div>
                      <div class='mb-3'>
                        <label class='form-label'>Product Image</label>
                        <input type='file' name='prodImage' class='form-control' accept='image/*'>";
                        if ($row['prodImage']) {
                          echo "<img src='../uploads/{$row['prodImage']}' class='inventory-img mt-2'>";
                        }
                        echo "
                      </div>
                    </div>
                    <div class='modal-footer'>
                      <button type='submit' name='update' class='btn btn-success'>Save changes</button>
                      <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
