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

// Query untuk mengambil data game
$sql_games = "SELECT id_game, nama_game, foto_game, link_game, clicks FROM game";
$result_games = $conn->query($sql_games);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Game - Pixel Playground</title>
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

    <!-- Game Section -->
    <section class="community-section">
        <div class="section-header">
            <h2>Game Reviews</h2>
            <p class="section-description">Lihat Review Game yang Kamu Mau di Sini!</p>
        </div>

        <div class="community-grid">
            <?php
            if ($result_games->num_rows > 0) {
                while ($row = $result_games->fetch_assoc()) {
                    $imagePath = $row["foto_game"];
                    echo '<div class="community-card">';
                    echo '<img src="' . htmlspecialchars($imagePath) . '" alt="' . htmlspecialchars($row["nama_game"]) . '" class="community-cover">';
                    echo '<h2 class="community-title">' . htmlspecialchars($row["nama_game"]) . '</h2>';
                    echo '<div class="click-count">' . number_format($row["clicks"]) . ' views</div>';
                    echo '<a href="javascript:void(0);" onclick="handleClick(\'game\', ' . $row["id_game"] . ', \'reviewgame.php?id=' . $row["id_game"] . '\')" class="join-btn">Show Review</a>';
                    echo '</div>';
                }
            } else {
                echo '<div class="no-data">Tidak ada game ditemukan.</div>';
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