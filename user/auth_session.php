<?php
session_start();

$sql = "SELECT * FROM customer WHERE email = '{$_SESSION['email']}'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

    if(!isset($_SESSION['email'])) {
        echo "<script>
            alert('Please login to access this page.');
            window.location.href = 'index.php';
        </script>";
    }
    else{
        $_SESSION['customerID'] = $user['customerID'];
        $_SESSION['fname'] = $user['fname'];
        $_SESSION['lname'] = $user['lname'];
        $_SESSION['email'] = $user['email'];
    }
?>