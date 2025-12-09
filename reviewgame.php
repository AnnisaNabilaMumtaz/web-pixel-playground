<?php
session_start();
include 'db_connect.php';

// Periksa apakah parameter id_game tersedia dan valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Loading.");
}
$id_game = intval($_GET['id']);

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    die("Anda harus login untuk memberikan komentar.");
}

$id_user_login = $_SESSION['id_user'];

/// Handle user session
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    
    // Modified query to get username from user table and photo from profile table
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

// Ambil data pengguna yang login
$sql_user = "SELECT user.username, profile.foto 
             FROM user 
             JOIN profile ON user.id_user = profile.id_user 
             WHERE user.id_user = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param('i', $id_user_login);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows === 0) {
    die("Data pengguna tidak ditemukan.");
}

$user_login = $result_user->fetch_assoc();

// Handle POST request untuk menambahkan komentar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['komentar'], $_POST['id_game'], $_POST['rating'])) {
    $id_game = intval($_POST['id_game']);
    $komentar = $conn->real_escape_string($_POST['komentar']);
    $rating = intval($_POST['rating']);

    $sql_insert = "INSERT INTO review (id_game, id_user, rating, komentar) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param('iiis', $id_game, $id_user_login, $rating, $komentar);

    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
    } else {
        // Redirect untuk mencegah duplikasi form submission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Ambil data game berdasarkan id_game
$sql_game = "SELECT * FROM game WHERE id_game = ?";
$stmt_game = $conn->prepare($sql_game);
$stmt_game->bind_param('i', $id_game);
$stmt_game->execute();
$result_game = $stmt_game->get_result();

if ($result_game->num_rows === 0) {
    die("Game tidak ditemukan.");
}

$game = $result_game->fetch_assoc();

// Ambil data review untuk game ini
$sql_reviews = "
    SELECT review.*, user.username, profile.foto 
    FROM review 
    JOIN user ON review.id_user = user.id_user 
    JOIN profile ON user.id_user = profile.id_user 
    WHERE review.id_game = ?";
$stmt_reviews = $conn->prepare($sql_reviews);
$stmt_reviews->bind_param('i', $id_game);
$stmt_reviews->execute();
$result_reviews = $stmt_reviews->get_result();

$reviews = $result_reviews->num_rows > 0 ? $result_reviews->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Game - <?php echo htmlspecialchars($game['nama_game']); ?></title>
    <link rel="stylesheet" href="reviewgame.css?v=2">
    <link rel="stylesheet" href="navstyle2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <style>
        .tab-pane {
            display: none;
        }
        .tab-pane.active {
            display: block;
        }
        .tab.active {
            font-weight: bold;
        }
        .rating .star {
            cursor: pointer;
        }
        .rating .selected {
            color: gold;
        }
    </style>
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

    <div class="game-header">
        <div class="wrapper">
            <div class="game-img">
                <img src="<?php echo htmlspecialchars($game['foto_game']); ?>" alt="Game <?php echo htmlspecialchars($game['nama_game']); ?>" class="game-image">
            </div>
        </div>

        <div class="game-info">
            <h1><?php echo htmlspecialchars($game['nama_game']); ?></h1>
            <p><?php echo htmlspecialchars($game['developer']); ?></p>
            <p>Usia <?php echo htmlspecialchars($game['batas_usia']); ?>+</p>
            <div class="game-buttons">
                <a href="<?php echo htmlspecialchars($game['link_game']); ?>" class="play-now">Mainkan Sekarang</a>
                <a href="<?php echo htmlspecialchars($game['link_mabar']); ?>" class="create-room">Buat Room Mabar</a>
            </div>
        </div>
    </div>

    <div class="game-details">
        <nav class="tabs">
            <button class="tab active" data-tab="about">About</button>
            <button class="tab" data-tab="comments">Komentar</button>
            <button class="tab" data-tab="community">Komunitas</button>
        </nav>

        <div class="tab-content">
            <div class="tab-pane active" id="about">
                <h2>Deskripsi Game</h2>
                <p><?php echo htmlspecialchars($game['deskripsi']); ?></p>
            </div>

            <div class="tab-pane" id="comments">
                <div class="comments-section">
                    <form method="POST" class="comment-form">
                        <input type="hidden" name="id_game" value="<?php echo htmlspecialchars($game['id_game']); ?>">
                        <input type="hidden" name="id_user" value="<?php echo $id_user_login; ?>">
                        <input type="hidden" name="rating" value="0" id="rating-value">
                        <div class="rating" id="rating-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="star" data-value="<?php echo $i; ?>">⭐</span>
                            <?php endfor; ?>
                        </div>
                        <textarea name="komentar" placeholder="Tambahkan komentar Anda..." class="typing-input" id="comment-input" required></textarea>
                        <button type="submit" class="submit-btn">Send</button>
                    </form>
                            
                    <h2>Komentar</h2>
                    <div class="comments-list" id="comments-list">
                        <?php foreach ($reviews as $review): ?>
                            <div class="comment">
                                <div class="player-profile">
                                    <img src="<?php echo htmlspecialchars($review['foto']); ?>" alt="Foto Profil">
                                    <?php echo htmlspecialchars($review['username']); ?>
                                </div> 
                                <div class="comment-content">
                                    <p>"<?php echo htmlspecialchars($review['komentar']); ?>"</p>
                                    <p><?php echo str_repeat('⭐', $review['rating']); ?></p>
                                </div>   
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="community">
                <h2>Komunitas</h2>
                <p>Bergabung dengan komunitas game favoritmu di Pixel Playground untuk berdiskusi, berbagi tips, dan menemukan teman bermain!</p>
                <a href="<?php echo htmlspecialchars($game['link_komunitas']); ?>" class="join-community">Gabung Komunitas</a>
            </div>
        </div>
    </div>
    <script>
        // Handle tab switching
        const tabs = document.querySelectorAll('.tab');
        const tabPanes = document.querySelectorAll('.tab-pane');
        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                tabs.forEach(t => t.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));
                this.classList.add('active');
                document.getElementById(this.getAttribute('data-tab')).classList.add('active');
            });
        });

        // Handle star rating
        document.querySelectorAll('.star').forEach(star => {
            star.addEventListener('click', function () {
                document.querySelectorAll('.star').forEach(s => s.classList.remove('selected'));
                this.classList.add('selected');
                document.getElementById('rating-value').value = this.dataset.value;
            });
        });
    </script>
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