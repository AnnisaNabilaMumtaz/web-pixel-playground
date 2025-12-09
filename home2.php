<?php
include 'db_connection.php';
session_start();

if (isset($_SESSION['success_msg'])) {
    echo '<script>alert("' . $_SESSION['success_msg'] . '");</script>';
    unset($_SESSION['success_msg']);
}

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

// Query untuk mengambil data komunitas dengan jumlah views
$sql = "SELECT id_komunitas, nama_komunitas, foto_komunitas, deskripsi, link_komunitas, clicks 
        FROM komunitas 
        ORDER BY nama_komunitas ASC";
$result = $conn->query($sql);

$allowedExtensions = ['png', 'jpg', 'jpeg', 'gif'];

// Get top 5 most clicked games
$sql_games = "SELECT id_game, nama_game, foto_game, link_game, deskripsi, clicks 
              FROM game 
              ORDER BY clicks DESC 
              LIMIT 5";
$result_games = $conn->query($sql_games);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pixel Playground - Home</title>
    <link rel="stylesheet" href="home2.css">
    <link rel="stylesheet" href="navstyle2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
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

    <!-- Popular Games Section -->
    <section class="review-section">
        <div class="section-header">
            <h2>Game yang Populer</h2>
            <a href="pilihgame.php" class="view-all-btn">Lihat Semua Game</a>
        </div>
        <div class="game-grid">
            <?php
            if ($result_games->num_rows > 0) {
                while($row = $result_games->fetch_assoc()) {
                    $imagePath = $row["foto_game"];
                    $fileExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
                    echo '<div class="game-card" onclick="handleClick(\'game\', ' . $row["id_game"] . ', \'reviewgame.php?id=' . $row["id_game"] . '\')" style="cursor: pointer;">';
                    echo '<img src="' . htmlspecialchars($imagePath) . '" alt="' . htmlspecialchars($row["nama_game"]) . '" class="game-cover">';
                    echo '<div class="info-container">';
                    echo '<h2 class="game-title">' . htmlspecialchars($row["nama_game"]) . '</h2>';
                    echo '<div class="click-count">' . number_format($row["clicks"]) . ' views</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<div class='no-data'>No games found</div>";
            }
            ?>
        </div>
    </section>
    <!-- Popular Communities Section -->
    <section class="community-section">
        <div class="section-header">
            <h2>Komunitas yang Populer</h2>
            <a href="pilihkomunitas.php" class="view-all-btn">Lihat Semua Komunitas</a>
        </div>
        <div class="community-grid">
            <?php
            // Query untuk mendapatkan data 5 komunitas terpopuler berdasarkan jumlah klik
            $sql_communities = "SELECT id_komunitas, nama_komunitas, foto_komunitas, clicks, link_komunitas 
                                FROM komunitas 
                                ORDER BY clicks DESC 
                                LIMIT 5";
            $result_communities = $conn->query($sql_communities);

            if ($result_communities->num_rows > 0) {
                while ($row = $result_communities->fetch_assoc()) {
                    $imagePath = $row["foto_komunitas"];
                    $linkKomunitas = htmlspecialchars($row["link_komunitas"]); // Link komunitas dari database
                    echo '<div class="community-card" onclick="handleClick(\'komunitas\', ' . $row["id_komunitas"] . ', \'' . $linkKomunitas . '\')" style="cursor: pointer;">';
                    echo '<img src="' . htmlspecialchars($imagePath) . '" alt="' . htmlspecialchars($row["nama_komunitas"]) . '" class="community-cover">';
                    echo '<div class="info-container">';
                    echo '<h2 class="community-title">' . htmlspecialchars($row["nama_komunitas"]) . '</h2>';
                    echo '<div class="click-count">' . number_format($row["clicks"]) . ' views</div>';
                    echo '</div>';
                    echo '</div>';
                }
                } else {
                    echo "<div class='no-data'>No communities found</div>";
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