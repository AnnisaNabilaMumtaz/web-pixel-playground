<?php
include 'db_connection.php';

// Get top 5 most clicked games
$sql_games = "SELECT id_game, nama_game, foto_game, link_game, deskripsi, clicks 
              FROM game 
              ORDER BY clicks DESC 
              LIMIT 5";
$result_games = $conn->query($sql_games);

// Get top 5 most clicked communities
$sql_communities = "SELECT id_komunitas, nama_komunitas, foto_komunitas, link_komunitas, deskripsi, clicks 
                   FROM komunitas 
                   ORDER BY clicks DESC 
                   LIMIT 5";
$result_communities = $conn->query($sql_communities);

$allowedExtensions = ['png', 'jpg', 'jpeg', 'gif'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pixel Playground - Home</title>
    <link rel="stylesheet" href="home2.css?v=2">
    <link rel="stylesheet" href="navstyle2.css">
    <link rel="stylesheet" href="banner.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
</head>

<body>
    <!-- Navigation -->
    <nav class="nav container">
    <img src="LogoPP.png" class="img_logo">
    <a href="login_form.php" class="nav__logo">Pixel Playground</a>

    <div class="nav__menu" id="nav-menu">
      <ul class="nav__list">
        <li class="nav__item">
          <a href="login_form.php" class="nav__link">Home</a>
        </li>

        <li class="nav__item">
          <a href="login_form.php" class="nav__link">Game</a>
        </li>

        <li class="nav__item">
          <a href="login_form.php" class="nav__link">Community</a>
        </li>
    </div>

    <div class="nav__actions">

      <!-- Login button -->
      <a href="login_form.php" class="ri-user-line nav__login" id="login-btn"></a>

      <!-- Toggle button -->
      <div class="nav__toggle" id="nav-toggle">
        <i class="ri-menu-line"></i>
      </div>
    </div>
  </nav>
  <header>
    <div class="banner">
      <h1>Pixel Playground: </h1>
      <h2>Your Gateway to Gaming Adventures!</h2>
      <p>Join Pixel Playground and explore a world of game reviews, community discussions, and team-based gaming
        sessions. Start your journey now!</p>
    </div>
  </header>
    <!-- Popular Games Section -->
    <section class="review-section">
        <div class="section-header">
            <h2>Game yang Populer</h2>
            <a href="login_form.php" class="view-all-btn">Lihat Semua Game</a>
        </div>
        <div class="game-grid">
            <?php
            if ($result_games->num_rows > 0) {
                while ($row = $result_games->fetch_assoc()) {
                    $imagePath = "" . $row["foto_game"]; // Sesuaikan dengan folder Anda
                    echo '<div class="game-card" onclick="window.location.href=\'login_form.php?id=' . $row["id_game"] . '\'" style="cursor: pointer;">';
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
            <a href="login_form.php" class="view-all-btn">Lihat Semua Komunitas</a>
        </div>
        <div class="community-grid">
            <?php
            if ($result_communities->num_rows > 0) {
                while ($row = $result_communities->fetch_assoc()) {
                    $imagePath = "" . $row["foto_komunitas"]; // Sesuaikan dengan folder Anda
                    echo '<div class="community-card" onclick="window.location.href=\'login_form.php?id=' . $row["id_komunitas"] . '\'" style="cursor: pointer;">';
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
</body>

</html>