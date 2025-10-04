<?php
// verification.php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
include 'connection.php';

$page_title = "Toy Brigade | Verify Your Account";
$page_css = ['../css/verification.css']; // Optional: page-specific CSS

include __DIR__ . '/partials/header.php';

// Check if the form is submitted
if (isset($_POST["verifyBtn"])) {
    $otp = $_SESSION['otp'];          // Retrieve the OTP stored in the session
    $email = $_SESSION['mail'];       // Retrieve the user's email stored in the session
    $otp_code = trim($_POST['otp_code']);

    if ($otp != $otp_code) {
        echo "<script>alert('Invalid OTP code');</script>";
    } else {
        // Mark user as verified in the database
        $conn->query("UPDATE customer SET status = 1 WHERE email = '$email'");

        // Fetch user details for personalization
        $user_query = $conn->query("SELECT fname FROM customer WHERE email = '$email'");
        $user = $user_query->fetch_assoc();
        $firstname = $user['fname'];

        echo "<script>
            alert('Account verified successfully. Welcome, $firstname!');
        </script>";
        session_destroy(); // Clear session data
        echo "<script>
            window.location.replace('index.php');
        </script>";
    }
}

// Check if OTP has expired
if (isset($_SESSION['otp_time']) && time() > $_SESSION['otp_time']) {
    echo "<script>alert('OTP has expired. Please register again.'); window.location.replace('register.php');</script>";
    session_destroy();
    exit();
}

// Handle OTP verification
if (isset($_POST["verifyBtn"])) {
    $otp = $conn->real_escape_string(trim($_POST["otp"]));
    
    if (!empty($otp)) {
        if ($otp == $_SESSION['otp']) {
            // OTP is correct, activate the user
            $email = $_SESSION['mail'];
            $result = mysqli_query($conn, "UPDATE customer SET status = 1 WHERE email = '$email'");
            
            if ($result) {
                echo "<script>alert('Account verified successfully! You can now login.'); window.location.replace('index.php');</script>";
                session_destroy();
            } else {
                echo "<script>alert('Verification failed. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Invalid OTP. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Please enter the OTP.');</script>";
    }
}

// Handle OTP resend
if (isset($_POST["resendBtn"])) {
    require "Mail/phpmailer/PHPMailerAutoload.php";
    $mail = new PHPMailer;

    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'kentguballa90@gmail.com'; 
    $mail->Password = 'qhrxghruxtqigryd';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Generate new OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_time'] = time() + (10 * 60); // Reset expiration to 10 minutes

    // Get user's first name for personalized email
    $email = $_SESSION['mail'];
    $user_query = mysqli_query($conn, "SELECT fname FROM customer WHERE email = '$email'");
    $user = mysqli_fetch_assoc($user_query);
    $firstname = $user['fname'];
    $lastname = $user['lname'];

    $mail->setFrom('kentguballa90@gmail.com', 'ToyBrigade');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Your new verification code";
    $mail->Body = "<p>Dear $firstname $lastname,</p>
    <h3>Your new OTP verification code is $otp</h3>
    <p>This OTP will expire in 10 minutes.</p>
    <br><br>
    <p>With regards,</p>
    <p>Toy Brigade Team</p>";

    if (!$mail->send()) {
        echo "<script>alert('Failed to resend OTP. Please try again.');</script>";
    } else {
        echo "<script>alert('New OTP sent to your email!');</script>";
    }
}
?>

<!-- Verification Section -->
<section class="verification-section d-flex align-items-center justify-content-center py-5" style="min-height: 80vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm border-0 rounded-4 playful-card">
                    <div class="card-body p-4 p-md-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="verification-icon mb-3">
                                <i class="fas fa-envelope-open-text fa-3x text-pastel"></i>
                            </div>
                            <h2 class="fw-bold mb-2" style="color: #ff85a2;">Verify Your Account</h2>
                            <p class="text-muted">
                                We've sent a 6-digit verification code to<br>
                                <strong><?php echo htmlspecialchars($_SESSION['mail']); ?></strong>
                            </p>
                        </div>

                        <!-- OTP Form -->
                        <form method="POST" action="verification.php">
                            <div class="mb-4">
                                <label for="otp" class="form-label fw-semibold">Enter Verification Code</label>
                                <input type="text" 
                                       class="form-control pastel-input text-center fw-bold fs-4" 
                                       id="otp" 
                                       name="otp_code" 
                                       maxlength="6" 
                                       placeholder="000000" 
                                       required 
                                       autocomplete="off"
                                       style="letter-spacing: 8px;">
                                <div class="form-text text-center">
                                    Code expires in <span id="countdown">10:00</span> minutes
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" name="verifyBtn" class="btn btn-pastel btn-lg fw-semibold">
                                    <i class="fas fa-check-circle me-2"></i>Verify Account
                                </button>
                                
                                <form method="POST" class="d-grid">
                                    <button type="submit" name="resendBtn" class="btn btn-outline-pastel">
                                        <i class="fas fa-redo-alt me-2"></i>Resend Code
                                    </button>
                                </form>
                            </div>
                        </form>

                        <!-- Help Text -->
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                Didn't receive the code? Check your spam folder or 
                                <a href="contact.php" class="text-decoration-none">contact support</a>.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Footer
include __DIR__ . '/partials/footer.php';
?>

<script>
// Countdown timer for OTP expiration
document.addEventListener('DOMContentLoaded', function() {
    const countdownElement = document.getElementById('countdown');
    let timeLeft = 10 * 60; // 10 minutes in seconds
    
    function updateCountdown() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        
        countdownElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeLeft <= 0) {
            clearInterval(timer);
            countdownElement.textContent = "00:00";
            alert('OTP has expired. Please request a new one.');
            document.querySelector('button[name="resendBtn"]').click();
        } else {
            timeLeft--;
        }
    }
    
    // Update every second
    const timer = setInterval(updateCountdown, 1000);
    updateCountdown(); // Initial call
    
    // Auto-focus OTP input
    document.getElementById('otp').focus();
    
    // Auto-tab between OTP digits (if using multiple inputs)
    document.getElementById('otp').addEventListener('input', function(e) {
        if (this.value.length === 6) {
            this.blur();
        }
    });
});
</script>