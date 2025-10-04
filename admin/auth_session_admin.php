<?php
session_start();

$sql = "SELECT * FROM admin WHERE username = '{$_SESSION['username']}'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

    if(!isset($_SESSION['email'])) {
        echo "<script>
            alert('Please login to access this page.');
            window.location.href = 'index.php';
        </script>";
    }
    else{
        $_SESSION['adminID'] = $user['adminID'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_fname'] = $user['fname'];
        $_SESSION['admin_lname'] = $user['lname'];
        $_SESSION['admin_role'] = $user['role'];
    }
?>