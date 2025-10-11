<?php
include 'connection.php';
include 'auth_session.php';

$page_title = "Toy Brigade | Edit Profile";
$page_css   = ['../css/edit_profile.css']; // optional, keep or remove

include __DIR__ . '/partials/header.php';   // outputs <!DOCTYPE html> + <head> + opens <body>
include __DIR__ . '/partials/navbar.php';   // shared navbar (dropdowns need footer bundle)

// Check if the user is logged in
if (!isset($_SESSION['customerID'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['customerID'];
$message = '';

// Fetch user data
$query = "SELECT fname, lname, email, address, lytnumber FROM customer WHERE customerID = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header('Location: logout.php');
    exit();
}

// Handle form submission for updating profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_fname = $_POST['fname'] ?? $user['fname'];
    $new_lname = $_POST['lname'] ?? $user['lname'];
    $new_email = $_POST['email'] ?? $user['email'];
    $new_address = $_POST['address'] ?? $user['address'];
    $new_lytnumber = $_POST['lytnumber'] ?? $user['lytnumber'];
    $new_password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Basic validation
    if (empty($new_fname) || empty($new_lname) || empty($new_email)) {
        $message = "Name and email cannot be empty.";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } elseif (!empty($new_password) && $new_password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        // Update name, email, address and contact number
        $update_query = "UPDATE customer SET fname = '$new_fname', lname = '$new_lname', email = '$new_email', address = '$new_address', lytnumber = '$new_lytnumber' WHERE customerID = $user_id";
        if (mysqli_query($conn, $update_query)) {
            $message = "Profile updated successfully.";
            // Update session data
            $_SESSION['fname'] = $new_fname;
            $_SESSION['lname'] = $new_lname;
            // Re-fetch user data
            $user['fname'] = $new_fname;
            $user['lname'] = $new_lname;
            $user['email'] = $new_email;
            $user['address'] = $new_address;
            $user['lytnumber'] = $new_lytnumber;
        } else {
            $message = "Error updating profile: " . mysqli_error($conn);
        }

        // Update password if provided
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_password_query = "UPDATE customer SET password = '$hashed_password' WHERE customerID = $user_id";
            if (mysqli_query($conn, $update_password_query)) {
                $message .= " Password updated successfully.";
            } else {
                $message .= " Error updating password: " . mysqli_error($conn);
            }
        }
    }
}
?>



    <div class="container-profile">
        <h1>Edit Profile</h1>
        <?php if ($message): ?>
            <div class="message <?php echo (strpos($message, 'Error') !== false) ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="edit_profile.php" method="POST">
            <div class="form-group">
                <label for="fname" class="form-label">First Name:</label>
                <input type="text" class="form-control" id="fname" name="fname" value="<?php echo htmlspecialchars($user['fname']); ?>" required>
            </div>
            <div class="form-group">
                <label for="lname" class="form-label">Last Name:</label>
                <input type="text" class="form-control" id="lname" name="lname" value="<?php echo htmlspecialchars($user['lname']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address" class="form-label">Shipping Address:</label>
                <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($user['address']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="lytnumber" class="form-label">Contact Number:</label>
                <input type="text" class="form-control" id="lytnumber" name="lytnumber" value="<?php echo htmlspecialchars($user['lytnumber']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">New Password (leave blank to keep current):</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirm New Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-pastel w-100">Update Profile</button>
            </div>
        </form>
    </div>
<?php
$page_scripts = []; // add '../js/profile.js?v=1' later if you need page JS
include __DIR__ . '/partials/footer.php'; // loads bootstrap bundle + nav.js, closes </body></html>
?>
</body>
</html>