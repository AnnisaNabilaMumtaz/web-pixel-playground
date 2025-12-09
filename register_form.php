<?php
session_start();
require_once 'db_connection.php';

function debugLog($message) {
    error_log(date('Y-m-d H:i:s') . " - " . $message . "\n");
}

function registerUser($conn, $username, $email, $password, $user_type) {
    debugLog("Starting registration for email: " . $email);
    
    // Check if email exists
    $stmt = $conn->prepare("SELECT id_user FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        debugLog("Email already exists: " . $email);
        return ["status" => "error", "message" => "Email already exists"];
    }
    
    // Generate password hash
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    debugLog("Generated hash length: " . strlen($hashed_password));
    debugLog("Generated hash: " . $hashed_password);
    
    // Insert new user
    $stmt = $conn->prepare("INSERT INTO user (username, email, password, jenis_user) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        debugLog("Prepare failed: " . $conn->error);
        return ["status" => "error", "message" => "Database error"];
    }
    
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $user_type);
    
    if ($stmt->execute()) {
        debugLog("Registration successful for email: " . $email);
        // Verify the stored hash
        $verify_stmt = $conn->prepare("SELECT password FROM user WHERE email = ?");
        $verify_stmt->bind_param("s", $email);
        $verify_stmt->execute();
        $verify_result = $verify_stmt->get_result();
        $stored_user = $verify_result->fetch_assoc();
        debugLog("Stored hash length: " . strlen($stored_user['password']));
        debugLog("Stored hash: " . $stored_user['password']);
        
        return ["status" => "success", "message" => "Registration successful"];
    } else {
        debugLog("Registration failed: " . $stmt->error);
        return ["status" => "error", "message" => "Registration failed"];
    }
}

$error = [];
if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $user_type = $_POST['jenis_user'];
    
    debugLog("Form submitted for email: " . $email);
    
    // Validate input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = "Invalid email format";
    }
    if (strlen($password) < 8) {
        $error[] = "Password must be at least 8 characters long";
    }
    if ($password !== $cpassword) {
        $error[] = "Passwords do not match";
    }
    
    if (empty($error)) {
        $result = registerUser($conn, $username, $email, $password, $user_type);
        
        // Check registration result
        if ($result['status'] === 'success') {
            $success = "Akun berhasil dibuat! Selamat datang di Pixel Playground 👾. Login dan mulai petualangan bermainmu sekarang!";
            $_SESSION['success_msg'] = $success;  // Store success message in session
            header('Location: login_form.php'); // Redirect to login page
            exit();
        } else {
            $error[] = $result['message'];  // Display registration error message
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link rel="stylesheet" href="registerLogin.css?v=2">
</head>
<body>
    <div class="form-container">
        <form action="" method="post">
            <h3>Buat Akun</h3>
            <?php
            if (!empty($error)) {
                foreach ($error as $err) {
                    echo '<span class="error-msg">' . htmlspecialchars($err) . '</span>';
                }
            }

            if (!empty($success)) {
                echo '<span class="success-msg">' . htmlspecialchars($success) . '</span>';
            }
            ?>
            <input type="text" name="username" required placeholder="Masukkan username Anda" 
                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            <input type="email" name="email" required placeholder="Masukkan email Anda" 
                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            <input type="password" name="password" required placeholder="Masukkan password Anda">
            <input type="password" name="cpassword" required placeholder="Konfirmasi password Anda">
            <select name="jenis_user">
                <option value="user">Pengunjung</option>
                <option value="admin">Admin</option>
            </select>
            <input type="submit" name="submit" value="Buat Akun Sekarang!" class="form-btn">
            <p>Sudah punya akun? <a href="login_form.php">Login sekarang</a></p>
        </form>
    </div>
</body>
</html>