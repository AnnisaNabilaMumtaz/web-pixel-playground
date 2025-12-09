<?php
include 'db_connection.php';
session_start();
if (!isset($_SESSION['admin_name'])) {
    header('Location: login system\login_form.php');
    exit();
}

// Periksa koneksi database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id_game, nama_game, developer, foto_game, created_at FROM game";
$result = $conn->query($sql);

// Periksa apakah query berhasil
if (!$result) {
    die("Query failed: " . $conn->error);  // Menampilkan pesan kesalahan jika query gagal
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pixel Playground</title>
  <link rel="stylesheet" href="tambahgame.css">
  <link rel="stylesheet" href="editgame.css">
  <link rel="stylesheet" href="detailgame.css">
  <!-- Boxicons -->
  <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- CSS -->
    <link rel="stylesheet" href="github.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

  <div class="container">
    <!-- SIDEBAR -->
<section id="sidebar">
    <a href="#" class="brand">
        <i class='bx bxs-game'></i>
        <span class="text">Pixel Playground Admin</span>
    </a>
    <ul class="side-menu top">
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'test.php' ? 'active' : ''; ?>">
            <a href="test.php">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin_game_page.php' ? 'active' : ''; ?>">
            <a href="admin_game_page.php">
                <i class='bx bxs-joystick'></i>
                <span class="text">Games</span>
            </a>
        </li>
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin_komunitas_page.php' ? 'active' : ''; ?>">
            <a href="admin_komunitas_page.php">
                <i class='bx bxs-message-dots'></i>
                <span class="text">Community</span>
            </a>
        </li>
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin_profile.php' ? 'active' : ''; ?>">
            <a href="admin_profile.php">
                <i class='bx bxs-user'></i>
                <span class="text">Profile</span>
            </a>
        </li>
    </ul>
    <ul class="side-menu">
        <li>
            <a href="logout.php" class="logout">
                <i class='bx bxs-log-out-circle'></i>
                <span class="text">Logout</span>
            </a>
        </li>
    </ul>
</section>
<!-- CONTENT -->
<section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">Categories</a>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Daftar Game</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="admin_game_page.php">Daftar Game</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="test.php">Dashboard</a>
                        </li>
                    </ul>
                </div>
            </div>
    <!-- Main Content -->
    <div class="main-content">
      <a class="btn btn-primary" id="openAddGamePopup">Tambah Game</a>
      <table class="games-table">
        <thead>
          <tr>
            <th>Foto</th>
            <th>Nama Game</th>
            <th>Developer</th>
            <th>Created at</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) { ?>
          <tr>
            <td>
              <?php if (file_exists($row['foto_game'])): ?>
                <img src="<?= htmlspecialchars($row['foto_game']); ?>" width="100">
              <?php else: ?>
                <img src="uploads/default.jpg" width="100"> <!-- Gambar default jika tidak ada -->
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['nama_game']); ?></td>
            <td><?= htmlspecialchars($row['developer']); ?></td>
            <td><?= htmlspecialchars($row['created_at']); ?></td>
            <td>
              <a href="javascript:void(0);" class="btn btn-detail" onclick="openDetailGamePopup(<?= $row['id_game']; ?>)">Detail</a>
              <a href="javascript:void(0);" class="btn btn-edit" onclick="openEditGamePopup(<?= $row['id_game']; ?>)">Edit</a>
              <a href="delete_game.php?id=<?= $row['id_game']; ?>" class="btn btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus game ini?');">Hapus</a>
            </td>
          </tr>
          <?php } } else { ?>
          <tr><td colspan="5">No records found</td></tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Popup for Add Game -->
  <div id="addGamePopup" class="popup">
    <div class="popup-content">
      <span class="close-button">&times;</span>
      <h1>Tambah Game</h1>
      <form action="add_game_process.php" method="post" enctype="multipart/form-data">
        <label>Nama Game:</label><br>
        <input type="text" name="nama_game" required><br><br>

        <label>Developer:</label><br>
        <input type="text" name="developer" required><br><br>

        <label>Batas Usia:</label><br>
        <input type="number" name="batas_usia" required><br><br>

        <label>Link Game:</label><br>
        <input type="text" name="link_game" required><br><br>

        <label>Deskripsi:</label><br>
        <textarea name="deskripsi" required></textarea><br><br>

        <label>Foto Game:</label><br>
        <input type="file" name="foto_game" required><br><br>

        <label>Link Mabar:</label><br>
        <input type="text" name="link_mabar"><br><br>

        <label>Link Komunitas:</label><br>
        <input type="text" name="link_komunitas"><br><br>

        <button type="submit">Tambah Game</button>
      </form>
    </div>
  </div>

  <!-- Popup untuk Detail Game -->
  <div id="detailGamePopup" class="detail-game-popup" style="display: none;">
    <div class="detail-game-popup-content">
      <span class="close-button" onclick="closeDetailGamePopup()">&times;</span>
      <div id="detailGameContent"></div>
    </div>
  </div>

  <!-- Popup untuk Edit Game -->
  <div id="editGamePopup" class="popup" style="display: none;">
    <div class="popup-content">
      <span class="close-button" onclick="closeEditGamePopup()">&times;</span>
      <h1>Edit Game</h1>
      <div id="editGameForm"></div>
    </div>
  </div>

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

  // Fungsi untuk membuka pop-up Detail Game
  function openDetailGamePopup(id_game) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "detail_game_popup.php?id=" + id_game, true);
    xhr.onload = function() {
      if (xhr.status === 200) {
        document.getElementById('detailGameContent').innerHTML = xhr.responseText;
        document.getElementById('detailGamePopup').style.display = 'block';
      }
    };
    xhr.send();
  }

  // Fungsi untuk menutup pop-up
  function closeDetailGamePopup() {
    document.getElementById('detailGamePopup').style.display = 'none';
  }

  // Fungsi untuk membuka pop-up Edit Game
  function openEditGamePopup(id_game) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "edit_game_popup.php?id=" + id_game, true);
    xhr.onload = function() {
      if (xhr.status === 200) {
        document.getElementById('editGameForm').innerHTML = xhr.responseText;
        document.getElementById('editGamePopup').style.display = 'block';
      }
    };
    xhr.send();
  }

  function closeEditGamePopup() {
    document.getElementById('editGamePopup').style.display = 'none';
  }
</script>

<script src="tambahgame.js"></script>
</body>
</html>
