<?php
include "connection.php";
session_start();

if (isset($_POST['adminSignupBtn'])) {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $username = trim($_POST['username']);
    $role = trim($_POST['role']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($fname) || empty($lname) || empty($username) || empty($password)) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
        exit;
    }

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit;
    }

    // Check if username already exists
    $check_sql = "SELECT * FROM admin WHERE username = '$username'";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Username already exists!'); window.history.back();</script>";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into admin table
    $insert_sql = "INSERT INTO admin (fname, lname, username, role, password) 
                   VALUES ('$fname', '$lname', '$username', '$role', '$hashed_password')";
    
    if (mysqli_query($conn, $insert_sql)) {
        // Get the newly created admin ID
        $admin_id = mysqli_insert_id($conn);
        
        // Set admin session variables
        $_SESSION['admin_id'] = $admin_id;
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin_fname'] = $fname;
        $_SESSION['admin_lname'] = $lname;
        $_SESSION['admin_role'] = $role;
        
        // Redirect to admin dashboard
        header("Location: AdminIndex.php");
        exit;
    } else {
        echo "<script>alert('Error creating admin account: " . mysqli_error($conn) . "'); window.history.back();</script>";
        exit;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup - Toy Brigade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .signup-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        .signup-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .signup-header h2 {
            margin: 0;
            font-weight: 700;
        }
        .signup-body {
            padding: 30px;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-signup {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-header">
            <h2><i class="fas fa-user-shield me-2"></i>Admin Signup</h2>
            <p class="mb-0">Create a new admin account</p>
        </div>
        <div class="signup-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fname" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="fname" name="fname" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lname" name="lname" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="admin">Admin</option>
                        <option value="superadmin">Super Admin</option>
                        <option value="moderator">Moderator</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" name="adminSignupBtn" class="btn btn-signup w-100">
                    <i class="fas fa-user-plus me-2"></i>Create Admin Account
                </button>
            </form>
            
            <div class="login-link">
                <p class="mt-3 mb-0">Already have an account? 
                    <a href="index.php" class="text-decoration-none">Go to Login</a>
                </p>
            </div>
            
            <div class="alert alert-info mt-3" role="alert">
                <small><i class="fas fa-info-circle me-1"></i> 
                    This is a temporary admin signup page. Remember to remove or secure this file in production.
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password confirmation validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                return false;
            }
        });
    </script>
</body>
</html>