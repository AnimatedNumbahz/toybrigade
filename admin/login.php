<?php
include __DIR__ . '/partials/header.php'; 
session_start();
include "connection.php";

if (isset($_POST['loginBtn'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // First, check if the email exists in admin table
    $admin_sql = "SELECT * FROM admin WHERE username = '$email'";
    $admin_result = mysqli_query($conn, $admin_sql);
    
    if ($admin_result && mysqli_num_rows($admin_result) > 0) {
        $admin = mysqli_fetch_assoc($admin_result);
        
        // Verify the password for admin
        if (password_verify($password, $admin['password'])) {
            // Admin login successful - set admin session variables
            $_SESSION['adminID'] = $admin['adminID'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_fname'] = $admin['fname'];
            $_SESSION['admin_lname'] = $admin['lname'];
            $_SESSION['admin_role'] = $admin['role'];
            
            // Redirect to admin dashboard
            header("Location: ../admin/AdminIndex.php");
            exit;
        } else {
            // Invalid password
            echo "<script>
                alert('Invalid email or password');
                window.location.href = 'login.php';
            </script>";
        }
    } else {
        // If not admin, check if the email exists in customer table
        $customer_sql = "SELECT * FROM customer WHERE email = '$email'";
        $customer_result = mysqli_query($conn, $customer_sql);
        
        if ($customer_result && mysqli_num_rows($customer_result) > 0) {
            $user = mysqli_fetch_assoc($customer_result);
            
            // Verify the password (compare plain text with hashed password from database)
            if (password_verify($password, $user['password'])) {
                // Check if customer has verified their email
                if ($user['status'] == 0) {
                    // User not verified - redirect to verification page
                    $_SESSION['mail'] = $user['email'];
                    echo "<script>
                        alert('Please verify your email address before logging in.');
                        window.location.href = 'verification.php';
                    </script>";
                    exit;
                } else {
                    // Customer login successful - set customer session variables
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['fname'] = $user['fname'];
                    $_SESSION['lname'] = $user['lname'];
                    $_SESSION['customerID'] = $user['customerID'];
                    
                    // Redirect to customer index page
                    header("Location: index.php");
                    exit;
                }
            } else {
                // Invalid password
                echo "<script>
                    alert('Invalid email or password');
                    window.location.href = 'login.php';
                </script>";
            }
        } else {
            // Email not found in either table
            echo "<script>
                alert('Invalid email or password');
                window.location.href = 'login.php';
            </script>";
        }
    }
}

// If not submitting login form, show the login page
?>
<body>
    <!-- Navbar -->
    <?php include 'partials/navbar.php'; ?>

    <!-- Login Section -->
    <section class="login-section">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-10">
                    <div class="login-card">
                        <div class="row g-0">
                            <!-- Login Form -->
                            <div class="col-lg-6">
                                <div class="login-body">
                                    <div class="text-center mb-4">
                                        <h1 class="login-title">Welcome Back!</h1>
                                        <p class="login-subtitle">Sign in to your Toy Brigade account</p>
                                    </div>
                                    
                                    <form method="POST" action="">
                                        <div class="mb-4">
                                            <label for="email" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                                        </div>
                                        
                                        <div class="mb-4 form-check">
                                            <input type="checkbox" class="form-check-input" id="remember">
                                            <label class="form-check-label" for="remember">Remember me</label>
                                        </div>
                                        
                                        <button type="submit" name="loginBtn" class="btn login-btn">Login</button>
                                        
                                        <div class="login-links">
                                            <p>Don't have an account? <a href="register.php">Sign up here</a></p>
                                            <p><a href="forgot-password.php">Forgot your password?</a></p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Login Image/Graphics -->
                            <div class="col-lg-6 login-image">
                                <img src="../images/kids-playing-toys.svg" alt="Kids Playing with Toys">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'partials/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="js/main.js"></script>
</body>
</html>

<?php
$conn->close();
?>