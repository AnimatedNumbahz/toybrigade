<?php
include 'connection.php';
// include 'auth_session_admin.php';
// include 'admin_guard.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Toy Brigade | Admin Dashboard</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Baloo+2:wght@400;600&family=Playfair+Display:wght@400;600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> 
  <link rel="stylesheet" href="adminSTYLE.css">

  <style>
    :root {
      --beige-primary: #f5f1e6;
      --beige-secondary: #e8e0d4;
      --beige-accent: #d6c9b8;
      --beige-dark: #a89a8a;
      --beige-darker: #8a7c6c;
      --beige-text: #5a5248;
      --beige-light: #faf8f3;
      --beige-shadow: rgba(166, 154, 138, 0.15);
    }
    
    body {
      background: var(--beige-light);
      font-family: 'Inter', sans-serif;
      color: var(--beige-text);
      line-height: 1.6;
    }

    /* Top Navbar */
    .admin-navbar {
      background: #fff;
      border-bottom: 1px solid var(--beige-secondary);
      box-shadow: 0 4px 12px var(--beige-shadow);
      padding: 0.8rem 0;
    }
    
    .admin-navbar .navbar-brand {
      font-family: 'Fredoka One', cursive;
      color: var(--beige-darker);
      font-size: 1.5rem;
    }
    
    .admin-navbar .nav-link {
      color: var(--beige-text);
      font-weight: 500;
      border-radius: 16px;
      padding: 8px 16px;
      margin: 0 4px;
      transition: all 0.25s ease;
    }
    
    .admin-navbar .nav-link:hover,
    .admin-navbar .nav-link.active {
      background: var(--beige-secondary);
      color: var(--beige-darker);
    }
    
    .admin-navbar .btn-logout {
      border-radius: 16px;
      background: var(--beige-darker);
      color: #fff;
      font-weight: 500;
      border: none;
      padding: 8px 18px;
      transition: all 0.25s ease;
    }
    
    .admin-navbar .btn-logout:hover {
      background: var(--beige-text);
      transform: translateY(-2px);
    }

    /* Main Content */
    .main-content {
      padding: 40px 20px;
    }
    
    .main-content h1 {
      font-family: "Playfair Display", serif;
      font-size: 2.4rem;
      color: var(--beige-darker);
      font-weight: 600;
      margin-bottom: 0.5rem;
    }
    
    .subtitle {
      color: var(--beige-dark);
      font-size: 1.05rem;
      margin-bottom: 2rem;
    }

    /* Dashboard Cards */
    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 24px;
      margin-top: 30px;
    }
    
    .card {
      border: 0;
      border-radius: 18px;
      background: #fff;
      box-shadow: 0 6px 18px var(--beige-shadow);
      text-align: center;
      padding: 28px 20px;
      transition: transform 0.25s ease, box-shadow 0.25s ease;
      position: relative;
      overflow: hidden;
    }
    
    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, var(--beige-accent), var(--beige-dark));
    }
    
    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 24px var(--beige-shadow);
    }
    
    .card i {
      font-size: 2.2rem;
      color: var(--beige-dark);
      margin-bottom: 14px;
      background: var(--beige-primary);
      width: 70px;
      height: 70px;
      line-height: 70px;
      border-radius: 50%;
      text-align: center;
      margin: 0 auto 16px;
    }
    
    .card h3 {
      font-weight: 600;
      margin-bottom: 8px;
      color: var(--beige-darker);
      font-size: 1.1rem;
    }
    
    .card span {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--beige-text);
      display: block;
      margin-top: 6px;
    }

    /* Customers Table */
    .table-wrapper {
      margin-top: 50px;
      background: #fff;
      padding: 24px;
      border-radius: 18px;
      box-shadow: 0 6px 18px var(--beige-shadow);
      overflow-x: auto;
    }
    
    .table-wrapper h2 {
      font-family: "Playfair Display", serif;
      color: var(--beige-darker);
      margin-bottom: 20px;
      font-size: 1.6rem;
      padding-bottom: 12px;
      border-bottom: 1px solid var(--beige-secondary);
    }
    
    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      min-width: 600px;
    }
    
    thead {
      background: var(--beige-primary);
      color: var(--beige-text);
    }
    
    th {
      padding: 16px;
      text-align: center;
      font-weight: 600;
      border-bottom: 2px solid var(--beige-accent);
    }
    
    td {
      padding: 14px 16px;
      text-align: center;
      border-bottom: 1px solid var(--beige-secondary);
    }
    
    tbody tr {
      transition: background 0.2s ease;
    }
    
    tbody tr:hover {
      background: var(--beige-primary);
    }
    
    .btn {
      border-radius: 10px;
      padding: 8px 14px;
      font-size: 0.9rem;
      font-weight: 500;
      transition: all 0.2s ease;
    }
    
    .btn.edit {
      background: var(--beige-dark);
      color: #fff;
      border: none;
    }
    
    .btn.edit:hover {
      background: var(--beige-darker);
      transform: translateY(-2px);
    }
    
    .btn.delete {
      background: #c1774b;
      color: #fff;
      border: none;
    }
    
    .btn.delete:hover {
      background: #a85f34;
      transform: translateY(-2px);
    }
    
    /* Logo styling */
    .navbar-brand img {
      height: 40px;
      border-radius: 8px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .cards {
        grid-template-columns: 1fr;
      }
      
      .admin-navbar .nav-link {
        margin: 4px 0;
        display: block;
      }
      
      .table-wrapper {
        padding: 16px;
      }
    }
  </style>
</head>
<body>

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg admin-navbar">
  <div class="container-fluid">
    
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="../images/logo2.png" alt="Toy Brigade Logo" class="logo"> 
      
    </a>

    <!-- Nav links (products, orders, customers, settings) -->
    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="#"><i class="fas fa-tachometer-alt me-1"></i> Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="Inventory.php"><i class="fas fa-box me-1"></i> Products</a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-shopping-cart me-1"></i> Orders</a></li>
        <li class="nav-item">
          <a class="nav-link" href="customer.php">
            <i class="fas fa-users me-1"></i> Customers
          </a>
        </li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-cog me-1"></i> Settings</a></li>
      </ul>
      <a href="logout.php" class="btn btn-logout ms-lg-3"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
  </div>
</nav>


<!-- Main Content -->
<div class="main-content container-fluid">
  <h1>Admin Dashboard</h1>
  <p class="subtitle">Manage products, orders, and customers here.</p>

  <!-- Cards -->
  <div class="cards">
    <div class="card">
      <i class="fas fa-box"></i>
      <h3>Products</h3>
      <span>120</span>
    </div>
    <div class="card">
      <i class="fas fa-shopping-cart"></i>
      <h3>Orders</h3>
      <span>58</span>
    </div>
    <div class="card">
      <i class="fas fa-users"></i>
      <h3>Customers</h3>
      <span>35</span>
    </div>
  </div>

  

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>