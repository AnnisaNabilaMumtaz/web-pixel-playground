<?php
include 'db_connect.php';
session_start();

/// Handle user session
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    $sql_profile = "
        SELECT user.username, profile.foto 
        FROM user 
        LEFT JOIN profile ON user.id_user = profile.id_user 
        WHERE user.id_user = ?";
    $stmt = $conn->prepare($sql_profile);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result_profile = $stmt->get_result();
    $profile = $result_profile->fetch_assoc();

    $username = $profile['username'] ?? 'Pengunjung';
    $foto = $profile['foto'] ?? 'uploads/default.png';
}

// Query untuk mengambil data komunitas dengan jumlah views
$sql = "SELECT id_komunitas, nama_komunitas, foto_komunitas, deskripsi, link_komunitas, clicks 
        FROM komunitas 
        ORDER BY nama_komunitas ASC";
$result = $conn->query($sql);

$allowedExtensions = ['png', 'jpg', 'jpeg', 'gif'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komunitas Game - Pixel Playground</title>
    <link rel="stylesheet" href="navstyle2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <link rel="stylesheet" href="pilihkomunitas.css?v=2">
</head>
<style>
    body {
        background-color: #1a1b26 !important;
    }
</style>

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

    <!-- Community Content -->
    <section class="community-section">
        <div class="section-header">
            <h2>Game Communities</h2>
            <p class="section-description">Bergabunglah dengan Komunitas Game Favoritmu!</p>
        </div>
        
        <div class="community-grid">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $imagePath = $row["foto_komunitas"];
                    $fileExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
                    
                    echo '<div class="community-card">';
                    if (in_array(strtolower($fileExtension), $allowedExtensions) && file_exists($imagePath)) {
                        echo '<img src="' . htmlspecialchars($imagePath) . '" alt="' . htmlspecialchars($row["nama_komunitas"]) . '" class="community-cover">';
                    } else {
                        echo '<img src="uploads/default.jpg" alt="Default Image" class="community-cover">';
                    }
                    echo '<h2 class="community-title">' . htmlspecialchars($row["nama_komunitas"]) . '</h2>';
                    echo '<div class="click-count">' . number_format($row["clicks"]) . ' views</div>';
                    echo '<a href="javascript:void(0);" onclick="handleClick(\'komunitas\', ' . $row["id_komunitas"] . ', \'' . htmlspecialchars($row["link_komunitas"]) . '\')" class="join-btn">Join Community</a>';
                    echo '</div>';
                }
            } else {
                echo '<div class="no-data">Tidak ada komunitas ditemukan.</div>';
            }
            ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Pixel Playground. All rights reserved.</p>
    </footer>

    <script>
        let subMenu = document.getElementById("subMenu");
        
        function toggleMenu() {
            subMenu.classList.toggle("open-menu");
        }
        
        function closeMenu() {
            subMenu.classList.remove("open-menu");
        }
    </script>
    <script>
    function handleClick(type, id, url) {
        // Kirim permintaan ke server untuk memperbarui jumlah klik
        fetch(`update_clicks.php?type=${type}&id=${id}`)
            .then(response => {
                if (!response.ok) {
                    console.error("Failed to update clicks.");
                }
                // Redirect ke URL setelah permintaan selesai
                window.location.href = url;
            })
            .catch(error => {
                console.error("Error:", error);
                // Tetap redirect jika ada kesalahan
                window.location.href = url;
            });
    }
</script>
</body>
</html>