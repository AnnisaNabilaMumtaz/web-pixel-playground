<?php
include 'db_connect.php';
session_start();

/// Handle user session
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    
    // Modified query to properly join user and profile tables
    $sql_profile = "
        SELECT u.username, p.foto 
        FROM user u
        LEFT JOIN profile p ON u.id_user = p.id_user 
        WHERE u.id_user = ?";
        
    $stmt = $conn->prepare($sql_profile);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result_profile = $stmt->get_result();
    $profile = $result_profile->fetch_assoc();

    // Set default values if profile data is not found
    $username = $profile['username'] ?? 'Pengunjung';
    $foto = $profile['foto'] ?? 'uploads/default.png';
    
    // Validate photo path
    if (!file_exists($foto) || empty($foto)) {
        $foto = 'uploads/default.png';
    }
}

$username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : $_SESSION['user_name'];
$query = "SELECT u.*, p.* FROM user u LEFT JOIN profile p ON u.id_user = p.id_user WHERE u.username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

if (!$user_data['id_profile']) {
    $insert_profile = "INSERT INTO profile (username, email, id_user) SELECT username, email, id_user FROM user WHERE username = ?";
    $stmt = $conn->prepare($insert_profile);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
}

$messages = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_username = $_POST['username'];
    $email = $_POST['email'];
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $has_changes = false;
    $password_changed = false;

    // Handle file upload
    if (isset($_FILES['foto']) && $_FILES['foto']['size'] > 0) {
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['foto']['type'], $allowed_types)) {
            $messages[] = ['type' => 'error', 'text' => 'Hanya file JPG, PNG, dan GIF yang diperbolehkan!'];
        } else {
            $file_extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $new_filename = uniqid() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                $foto = $upload_path;
                $has_changes = true;
                $messages[] = ['type' => 'success', 'text' => 'Foto profil berhasil diperbarui!'];
            } else {
                $messages[] = ['type' => 'error', 'text' => 'Gagal mengunggah foto!'];
            }
        }
    } else {
        $foto = $user_data['foto'];
    }

    // Handle password change
    if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
        if (empty($current_password)) {
            $messages[] = ['type' => 'error', 'text' => 'Password saat ini harus diisi!'];
        } elseif (empty($new_password)) {
            $messages[] = ['type' => 'error', 'text' => 'Password baru harus diisi!'];
        } elseif (empty($confirm_password)) {
            $messages[] = ['type' => 'error', 'text' => 'Konfirmasi password harus diisi!'];
        } elseif ($new_password !== $confirm_password) {
            $messages[] = ['type' => 'error', 'text' => 'Password baru dan konfirmasi tidak cocok!'];
        } else {
            // Verify current password
            $verify_password = "SELECT password FROM user WHERE id_user = ?";
            $stmt = $conn->prepare($verify_password);
            $stmt->bind_param("i", $user_data['id_user']);
            $stmt->execute();
            $result = $stmt->get_result();
            $current_hash = $result->fetch_assoc()['password'];
            
            if (password_verify($current_password, $current_hash)) {
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $password_changed = true;
                $has_changes = true;
            } else {
                $messages[] = ['type' => 'error', 'text' => 'Password saat ini tidak valid!'];
            }
        }
    }

    // Check username and email changes
    if ($new_username !== $user_data['username']) {
        $check_username = "SELECT id_user FROM user WHERE username = ? AND id_user != ?";
        $stmt = $conn->prepare($check_username);
        $stmt->bind_param("si", $new_username, $user_data['id_user']);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $messages[] = ['type' => 'error', 'text' => 'Username sudah digunakan!'];
        } else {
            $has_changes = true;
        }
    }

    if ($email !== $user_data['email']) {
        $check_email = "SELECT id_user FROM user WHERE email = ? AND id_user != ?";
        $stmt = $conn->prepare($check_email);
        $stmt->bind_param("si", $email, $user_data['id_user']);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $messages[] = ['type' => 'error', 'text' => 'Email sudah digunakan!'];
        } else {
            $has_changes = true;
        }
    }

    // If there are changes and no errors, proceed with update
    if ($has_changes && !array_filter($messages, fn($m) => $m['type'] === 'error')) {
        $conn->begin_transaction();

        try {
            if ($password_changed) {
                $update_password = "UPDATE user SET password = ? WHERE id_user = ?";
                $stmt = $conn->prepare($update_password);
                $stmt->bind_param("si", $new_password_hash, $user_data['id_user']);
                $stmt->execute();
                $messages[] = ['type' => 'success', 'text' => 'Password berhasil diperbarui!'];
            }

            // Update profile
            $update_profile = "UPDATE profile SET username = ?, email = ?, foto = ? WHERE id_user = ?";
            $stmt = $conn->prepare($update_profile);
            $stmt->bind_param("sssi", $new_username, $email, $foto, $user_data['id_user']);
            $stmt->execute();

            // Update user table
            $update_user = "UPDATE user SET username = ?, email = ? WHERE id_user = ?";
            $stmt = $conn->prepare($update_user);
            $stmt->bind_param("ssi", $new_username, $email, $user_data['id_user']);
            $stmt->execute();

            $conn->commit();
            
            if (isset($_SESSION['user_name'])) {
                $_SESSION['user_name'] = $new_username;
            } else {
                $_SESSION['admin_name'] = $new_username;
            }
            
            $messages[] = ['type' => 'success', 'text' => 'Profil berhasil diperbarui!'];
            header("Refresh:2");
        } catch (Exception $e) {
            $conn->rollback();
            $messages[] = ['type' => 'error', 'text' => 'Gagal memperbarui profil: ' . $e->getMessage()];
        }
    } elseif (!$has_changes) {
        $messages[] = ['type' => 'info', 'text' => 'Tidak ada perubahan yang dilakukan'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="navstyle2.css">
    <link rel="stylesheet" href="profile.css?v=2">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- CSS -->
    <link rel="stylesheet" href="github.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<!-- Navigation -->
<nav class="nav container">
        <img src="LogoPP.png" class="img_logo">
        <a href="home2.php" class="nav__logo">Pixel Playground</a>

        <div class="nav__menu" id="nav-menu">
            <ul class="nav__list">
                <li class="nav__item">
                    <a href="home2.php" class="nav__link">Home</a>
                </li>
                <li class="nav__item">
                    <a href="pilihgame.php" class="nav__link">Game</a>
                </li>
                <li class="nav__item">
                    <a href="pilihkomunitas.php" class="nav__link">Komunitas</a>
                </li>
            </ul>
        </div>

        <div class="nav__actions">
    <img src="<?php echo htmlspecialchars($foto); ?>" class="user-pic" onclick="toggleMenu()">

    <div class="sub-menu-wrap" id="subMenu" onmouseleave="closeMenu()">
        <div class="sub-menu">
            <div class="user-info">
                <img src="<?php echo htmlspecialchars($foto); ?>">
                <h2><?php echo htmlspecialchars($username); ?></h2>
            </div>
            <hr>
            
            <a href="profile.php" class="sub-menu-link">
                <p>Edit Profile</p>
                <span>></span>
            </a>
            <a href="logout.php" class="sub-menu-link">
                <p>Log Out</p>
                <span>></span>
            </a>
        </div>
    </div>
</div>
    </nav>

  <div class="container">
        <!-- MAIN -->
        <main>
        <?php foreach ($messages as $message): ?>
            <div class="message <?= $message['type'] ?>" role="alert">
                <?= htmlspecialchars($message['text']) ?>
            </div>
        <?php endforeach; ?>

        <form method="POST" enctype="multipart/form-data" onsubmit="return confirmChanges()">
            <div class="card">
                <div class="profile-section">
                    <img id="profile-picture" src="<?= htmlspecialchars($user_data['foto'] ?? 'default-profile.jpg') ?>" alt="Profile Picture">
                    <label for="fileUpload" class="btn btn-outline-primary">
                        Upload new photo
                        <input type="file" id="fileUpload" name="foto" class="account-settings-fileinput" onchange="previewImage(event)" style="display: none;">
                    </label>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user_data['username']) ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">E-mail</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user_data['email']) ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control" id="current_password">
                        <button type="button" onclick="togglePasswordVisibility('current_password')" class="eye-btn">
                            <i class='bx bx-show'></i>
                        </button>
                    </div>
                    <div class="form-group"> 
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="new_password" class="form-control" id="new_password">
                        <button type="button" onclick="togglePasswordVisibility('new_password')" class="eye-btn">
                        <i class='bx bx-show'></i>
                        </button>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="confirm_password" class="form-control" id="confirm_password">
                        <button type="button" onclick="togglePasswordVisibility('confirm_password')" class="eye-btn">
                            <i class='bx bx-show'></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="btn-container">
                <button type="reset" class="btn btn-outline-primary">Batal</button>
                <button type="submit" class="btn">Simpan Perubahan</button>
            </div>
        </form>
    </main>
    </div>
    
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let subMenu = document.getElementById("subMenu");

        function toggleMenu() {
            subMenu.classList.toggle("open-menu");
        }

        function closeMenu() {
            subMenu.classList.remove("open-menu");
        }
    </script>
    <!-- JavaScript untuk menampilkan foto yang dipilih -->
    <script>
        // Sidebar toggle
        const allSideDivider = document.querySelectorAll('#sidebar .divider');
        const allSideBar = document.querySelectorAll('#sidebar .side-menu.top li a');
        
        allSideBar.forEach(item => {
            const li = item.parentElement;
            item.addEventListener('click', function () {
                allSideBar.forEach(i => {
                    i.parentElement.classList.remove('active');
                })
                li.classList.add('active');
            })
        });

        // Toggle Sidebar
        const menuBar = document.querySelector('#content nav .bx.bx-menu');
        const sidebar = document.getElementById('sidebar');

        menuBar.addEventListener('click', function () {
            sidebar.classList.toggle('hide');
        });

        // Dark mode toggle
        const switchMode = document.getElementById('switch-mode');
        switchMode.addEventListener('change', function () {
            document.body.classList.toggle('dark');
        });

        // Fungsi untuk menampilkan atau menyembunyikan password
function togglePasswordVisibility(id) {
    const passwordField = document.getElementById(id);
    passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
}

// Fungsi untuk konfirmasi perubahan sebelum menyimpan
function confirmChanges() {
    return confirm("Apakah Anda yakin ingin menyimpan perubahan ini?");
}

// Auto-dismiss messages setelah 5 detik
document.addEventListener('DOMContentLoaded', function() {
    const messages = document.querySelectorAll('.message');
    messages.forEach(message => {
        setTimeout(() => {
            message.style.animation = 'fadeOut 0.5s ease-in forwards';
            setTimeout(() => {
                message.remove();
            }, 500);
        }, 5000);
    });
});

// Fungsi untuk pratinjau gambar yang dipilih
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const profilePicture = document.getElementById('profile-picture');
            profilePicture.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
    </script>
</body>
</html>