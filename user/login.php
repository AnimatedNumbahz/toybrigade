<?php
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
            header("Location: AdminIndex.php");
            exit;
        } else {
            // Invalid password
            echo "<script>
                alert('Invalid email or password');
                window.location.href = 'index.php';
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
                    window.location.href = 'index.php';
                </script>";
            }
        } else {
            // Email not found in either table
            echo "<script>
                alert('Invalid email or password');
                window.location.href = 'index.php';
            </script>";
        }
    }
}

$conn->close();
?>