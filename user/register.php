<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include('connection.php');

if (isset($_POST["signupBtn"])) {
    $firstname = $conn->real_escape_string(trim($_POST["fname"]));
    $lastname = $conn->real_escape_string(trim($_POST["lname"]));
    $email = $conn->real_escape_string(trim($_POST["email"]));
    $lytcard = $conn->real_escape_string(trim($_POST["lytcard"]));
    $password = $conn->real_escape_string(trim($_POST["password"]));

    // Check if the user already exists
    $check_query = mysqli_query($conn, "SELECT * FROM customer WHERE email = '$email' AND status = 1");
    $rowCount = mysqli_num_rows($check_query);

    if (!empty($firstname) && !empty($lastname) && !empty($email) && !empty($password)) {
        if ($rowCount > 0) {
            echo "<script>alert('User with activated email already exists!');</script>";
            $conn -> close();
            return;
        }

        $check_inactive_query = mysqli_query($conn, "SELECT * FROM customer WHERE email = '$email' AND status = 0");
        $rowCount = mysqli_num_rows($check_inactive_query);
        
        //updating instead of inserting if user exists but inactive
        if($rowCount > 0) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $result = mysqli_query($conn, 
             "UPDATE customer SET password = '$password_hash', status = 0, created_at = NOW() WHERE email = '$email'");

            // Generate OTP and store expiration time (10 minutes from now)
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['mail'] = $email;
            $_SESSION['otp_time'] = time() + (10 * 60); // OTP expires in 10 minutes

            // Send OTP email
            require "Mail/phpmailer/PHPMailerAutoload.php";
            $mail = new PHPMailer;

            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->Host = 'smtp.gmail.com'; // SendGrid SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'toybrigade.official@gmail.com'; 
            $mail->Password = 'uihdhmqsovxxbvdg'; // Your SendGrid API key as password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Recipient
            $mail->setFrom('toybrigade.official@gmail.com', 'ToyBrigade');
            $mail->addAddress($email); // User's email

            $mail->isHTML(true);
            $mail->Subject = "Your verification code";
            $mail->Body = "<p>Dear $firstname $lastname,</p>
            <h3>Your OTP verification code is $otp</h3>
            <p>This OTP will expire in 10 minutes.</p>
            <br><br>
            <p>With regards,</p>
            Toy Brigade Team";

            if (!$mail->send()) {
                echo "<script>alert('Register Failed, Invalid Email');</script>";
            } else {
                echo "<script>alert('Register Successfully, OTP sent to $email'); window.location.replace('verification.php');</script>";
            }
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert user details and time registered into the database
    
            $result = mysqli_query($conn, 
            "INSERT INTO customer (fname, lname, email, lytnumber, password, status) 
             VALUES ('$firstname', '$lastname', '$email', '$lytcard', '$password_hash', 0)");

            if ($result) {
                // Generate OTP and store expiration time (10 minutes from now)
                $otp = rand(100000, 999999);
                $_SESSION['otp'] = $otp;
                $_SESSION['mail'] = $email;
                $_SESSION['otp_time'] = time() + (10 * 60); // OTP expires in 10 minutes

                // Send OTP email
                require "Mail/phpmailer/PHPMailerAutoload.php";
                $mail = new PHPMailer;

                $mail->isSMTP();
                $mail->SMTPDebug = 0;
                $mail->Host = 'smtp.gmail.com'; // SendGrid SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'toybrigade.official@gmail.com'; 
                $mail->Password = 'uihdhmqsovxxbvdg'; // Your SendGrid API key as password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                // Recipient
                $mail->setFrom('toybrigade.official@gmail.com', 'ToyBrigade');
                $mail->addAddress($email); // User's email

                $mail->isHTML(true);
                $mail->Subject = "Your verification code";
                $mail->Body = "<p>Dear $firstname $lastname,</p>
                <h3>Your OTP verification code is $otp</h3>
                <p>This OTP will expire in 10 minutes.</p>
                <br><br>
                <p>With regards,</p>
                Toy Brigade Team";

                if (!$mail->send()) {
                    echo "<script>alert('Register Failed, Invalid Email');</script>";
                } else {
                    echo "<script>alert('Register Successfully, OTP sent to $email'); window.location.replace('verification.php');</script>";
                }
            } else {
                echo "<script>alert('Registration Failed. Please try again.');</script>";
            }
        }
    } else {
        echo "<script>alert('All fields are required.');</script>";
    }
}
$conn->close();
?>