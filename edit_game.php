<?php
include 'db_connection.php';

// Periksa apakah 'id' ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Parameter 'id' tidak ditemukan di URL.");
}

$id_game = intval($_GET['id']); // Konversi id menjadi integer
$sql = "SELECT * FROM game WHERE id_game = $id_game";
$result = $conn->query($sql);

// Periksa apakah query berhasil dan data ditemukan
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("Error: Data game tidak ditemukan.");
}

// Jika form dikirimkan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_game = $_POST['nama_game'];
    $developer = $_POST['developer'];
    $batas_usia = $_POST['batas_usia'];
    $link_game = $_POST['link_game'];
    $deskripsi = $_POST['deskripsi'];
    $link_mabar = $_POST['link_mabar'];
    $link_komunitas = $_POST['link_komunitas'];

    // Mengatur nama gambar default jika tidak ada gambar baru
    $foto_game = $row['foto_game']; // Gunakan foto lama jika tidak ada gambar baru

    // Proses upload gambar baru jika ada
    if (isset($_FILES['foto_game']) && $_FILES['foto_game']['error'] == 0) {
        $file_name = $_FILES['foto_game']['name'];
        $file_tmp = $_FILES['foto_game']['tmp_name'];
        $file_size = $_FILES['foto_game']['size'];
        $file_type = $_FILES['foto_game']['type'];
        
        // Tentukan direktori penyimpanan file gambar
        $upload_dir = 'uploads/';
        
        // Pastikan file yang di-upload adalah gambar
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        
        if (in_array($file_type, $allowed_types)) {
            // Membuat nama unik untuk gambar agar tidak ada duplikasi
            $file_path = $upload_dir . basename($file_name);
            if (move_uploaded_file($file_tmp, $file_path)) {
                $foto_game = $file_path; // Update nama gambar
            }
        }
    }

    // Menggunakan prepared statements untuk mencegah SQL Injection
    $stmt = $conn->prepare("UPDATE game SET nama_game = ?, developer = ?, batas_usia = ?, link_game = ?, deskripsi = ?, link_mabar = ?, link_komunitas = ?, foto_game = ? WHERE id_game = ?");
    $stmt->bind_param("ssssssssi", $nama_game, $developer, $batas_usia, $link_game, $deskripsi, $link_mabar, $link_komunitas, $foto_game, $id_game);
    if ($stmt->execute()) {
        header("Location: admin_game_page.php");
        exit;
    } else {
        die("Error: Gagal memperbarui data game.");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Game</title>
</head>
<body>

  <h1>Edit Game</h1>

  <form action="edit_game.php?id=<?= $id_game ?>" method="POST" enctype="multipart/form-data">
    <label>Nama Game:</label><br>
    <input type="text" name="nama_game" value="<?= htmlspecialchars($row['nama_game']) ?>" required><br><br>

    <label>Developer:</label><br>
    <input type="text" name="developer" value="<?= htmlspecialchars($row['developer']) ?>" required><br><br>

    <label>Batas Usia:</label><br>
    <input type="text" name="batas_usia" value="<?= htmlspecialchars($row['batas_usia']) ?>" required><br><br>

    <label>Link Game:</label><br>
    <input type="text" name="link_game" value="<?= htmlspecialchars($row['link_game']) ?>" required><br><br>

    <label>Deskripsi:</label><br>
    <textarea name="deskripsi" required><?= htmlspecialchars($row['deskripsi']) ?></textarea><br><br>

    <label>Foto Game:</label><br>
    <input type="file" name="foto_game"><br><br>

    <label>Link Mabar:</label><br>
    <input type="text" name="link_mabar" value="<?= htmlspecialchars($row['link_mabar']) ?>"><br><br>

    <label>Link Komunitas:</label><br>
    <input type="text" name="link_komunitas" value="<?= htmlspecialchars($row['link_komunitas']) ?>"><br><br>

    <button type="submit">Simpan Perubahan</button>
  </form>

</body>
</html>
