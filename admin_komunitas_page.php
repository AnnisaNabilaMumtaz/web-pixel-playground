<?php
include 'db_connection.php';
session_start();
if (!isset($_SESSION['admin_name'])) {
    header('Location: login system\login_form.php');
    exit();
}

// Join dengan tabel game untuk mendapatkan nama game
$sql = "SELECT k.*, g.nama_game 
        FROM komunitas k 
        JOIN game g ON k.id_game = g.id_game 
        ORDER BY k.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pixel Playground</title>
  <link rel="stylesheet" href="tambahgame.css">
  <link rel="stylesheet" href="editgame.css">
  <link rel="stylesheet" href="detail_komunitas.css">
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
                    <h1>Daftar Komunitas</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="admin_komunitas_page.php">Daftar Komunitas</a>
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
      <a class="btn btn-primary" id="openAddKomunitasPopup">Tambah Komunitas</a>
      <table class="games-table">
        <thead>
          <tr>
            <th>Foto</th>
            <th>Nama Komunitas</th>
            <th>Created at</th>
            <th>Action</th>
          </tr>
        </thead>
    <tbody>
        <?php if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td>
            <?php if (!empty($row['foto_komunitas']) && file_exists($row['foto_komunitas'])): ?>
                <img src="<?= htmlspecialchars($row['foto_komunitas']); ?>" width="100" alt="Foto Komunitas">
            <?php else: ?>
                <span>No image available</span>
            <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($row['nama_komunitas']); ?></td>
        <td><?= htmlspecialchars($row['created_at']); ?></td>
        <td>
            <a href="javascript:void(0);" class="btn btn-detail" onclick="openDetailKomunitasPopup(<?= $row['id_komunitas']; ?>)">Detail</a>
            <a href="javascript:void(0);" class="btn btn-edit" onclick="openEditKomunitasPopup(<?= $row['id_komunitas']; ?>)">Edit</a>
            <a href="delete_komunitas.php?id=<?= $row['id_komunitas']; ?>" class="btn btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus komunitas               ini?');">Hapus</a>
        </td>
    </tr>
    <?php } } else { ?>
    <tr><td colspan="5">No records found</td></tr>
    <?php } ?>
    </tbody>
      </table>
    </div>
  </div>

  <!-- Popup for Add Komunitas -->
  <div id="addKomunitasPopup" class="popup">
    <div class="popup-content">
      <span class="close-button">&times;</span>
      <span class="close-button" onclick="closeAddKomunitasPopup()">&times;</span>
      <h1>Tambah Komunitas</h1>
      <form action="add_komunitas_process.php" method="post" enctype="multipart/form-data">
        <label>Nama Komunitas:</label><br>
        <input type="text" name="nama_komunitas" required><br><br>

        <label>Deskripsi:</label><br>
        <textarea name="deskripsi" required></textarea><br><br>

        <label>Foto Komunitas:</label><br>
        <input type="file" name="foto_komunitas" required><br><br>

        <label>Link Komunitas:</label><br>
        <input type="text" name="link_komunitas" required><br><br>

        <label>ID Game:</label>
        <input type="number" name="id_game" required><br>

        <button type="submit">Tambah Komunitas</button>
      </form>
    </div>
  </div>

  <!-- Popup untuk Detail Game -->
  <div id="detailKomunitasPopup" class="detail-komunitas-popup" style="display: none;">
    <div class="detail-komunitas-popup-content">
      <span class="close-button" onclick="closeDetailKomunitasPopup()">&times;</span>
      <div id="detailKomunitasContent"></div>
    </div>
  </div>

  <!-- Popup untuk Edit Game -->
  <div id="editKomunitasPopup" class="popup" style="display: none;">
    <div class="popup-content">
      <span class="close-button" onclick="closeEditKomunitasPopup()">&times;</span>
      <h1>Edit Komunitas</h1>
      <div id="editKomunitasForm"></div>
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

  // buka pop up tambah komunitas
      document.getElementById("openAddKomunitasPopup").addEventListener("click", function() {
        document.getElementById("addKomunitasPopup").style.display = "block";
    });
    // Fungsi untuk menutup pop-up
      function closeAddKomunitasPopup() {
        document.getElementById('addKomunitasPopup').style.display = 'none';
      }

  // Fungsi untuk membuka pop-up Detail Game
  function openDetailKomunitasPopup(id_game) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "detail_komunitas_popup.php?id=" + id_game, true);
    xhr.onload = function() {
      if (xhr.status === 200) {
        document.getElementById('detailKomunitasContent').innerHTML = xhr.responseText;
        document.getElementById('detailKomunitasPopup').style.display = 'block';
      }
    };
    xhr.send();
  }

  // Fungsi untuk menutup pop-up
  function closeDetailKomunitasPopup() {
    document.getElementById('detailKomunitasPopup').style.display = 'none';
  }

  // Fungsi untuk membuka pop-up Edit Game
  function openEditKomunitasPopup(id_komunitas) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "edit_komunitas_popup.php?id=" + id_komunitas, true);
    xhr.onload = function() {
      if (xhr.status === 200) {
        document.getElementById('editKomunitasForm').innerHTML = xhr.responseText;
        document.getElementById('editKomunitasPopup').style.display = 'block';
      }
    };
    xhr.send();
  }

  function closeEditKomunitasPopup() {
    document.getElementById('editKomunitasPopup').style.display = 'none';
  }
</script>

<script src="tambahgame.js"></script>
</body>
</html>