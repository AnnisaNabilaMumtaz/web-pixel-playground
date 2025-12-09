<?php
// login_form.php
session_start();
require_once 'db_connection.php';
if (isset($_SESSION['success_msg'])) {
    echo '<script>alert("' . $_SESSION['success_msg'] . '");</script>';
    unset($_SESSION['success_msg']);
}

function loginUser($conn, $email, $password) {
    // Step 1: Log login attempt
    error_log("\n=== New Login Attempt ===");
    error_log("Attempting login for email: " . $email);
    
    // Step 2: Prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return ['status' => 'error', 'message' => 'Database error'];
    }
    
    $stmt->bind_param("s", $email);
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        return ['status' => 'error', 'message' => 'Database error'];
    }
    
    $result = $stmt->get_result();
    error_log("Query executed. Found rows: " . $result->num_rows);
    
    // Step 3: Check if user exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        error_log("User found in database");
        error_log("Stored hash: " . $user['password']);
        error_log("Input password length: " . strlen($password));
        
        // Step 4: Verify password
        $verified = password_verify($password, $user['password']);
        error_log("Password verification result: " . ($verified ? "SUCCESS" : "FAILED"));
        
        if ($verified) {
            error_log("Login successful for user: " . $user['username']);
            return [
                'status' => 'success',
                'user' => $user,
                'message' => 'Login successful'
            ];
        } else {
            error_log("Password verification failed");
            return ['status' => 'error', 'message' => 'Incorrect password'];
        }
    }
    
    error_log("No user found with this email");
    return ['status' => 'error', 'message' => 'User not found'];
}

$error = [];
$debug_info = []; // For showing debug info on page if needed

if (isset($_POST['submit'])) {
    // Sanitize and validate input
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Don't trim password as it might contain spaces
    
    error_log("\n=== Form Submission ===");
    error_log("Submitted email: " . $email);
    error_log("Submitted password length: " . strlen($password));
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = "Invalid email format";
    }
    
    if (empty($error)) {
        $result = loginUser($conn, $email, $password);
        error_log("Login result status: " . $result['status']);
        
        if ($result['status'] === 'success') {
            $user = $result['user'];
            $_SESSION['id_user'] = $user['id_user']; // Simpan id_user di sesi
        
            if ($user['jenis_user'] === 'admin') {
                $_SESSION['admin_name'] = $user['username'];
                $_SESSION['success_msg'] = "Selamat datang " . htmlspecialchars($user['username']) . "! 🎉 Anda memiliki akses penuh.";
                header('Location: test.php'); // Ganti dengan halaman admin yang sesuai
            } else {
                $_SESSION['user_name'] = $user['username'];
                $_SESSION['success_msg'] = "Selamat datang " . htmlspecialchars($user['username']) . "! 🎉 Nikmati pengalaman Anda.";
                header('Location: home2.php'); // Ganti dengan halaman pengunjung yang sesuai
            }
            exit();
        
        } else {
            $error[] = $result['message'];
            // Store debug info if needed
            $debug_info[] = "Login gagal: " . $result['message'];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="registerLogin.css?v=2">
</head>
<body>
    <div class="form-container">
        <form action="" method="post">
            <h3>Login</h3>
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
            <input type="email" name="email" required placeholder="Masukkan email Anda" 
                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            <input type="password" name="password" required placeholder="Masukkan password Anda">
            <input type="submit" name="submit" value="Login Sekarang!" class="form-btn">
            <p>Belum punya akun? <a href="register_form.php">Buat akun dahulu</a></p>
        </form>
    </div>
</body>
</html>