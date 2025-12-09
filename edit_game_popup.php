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
?>

<form method="POST" action="edit_game.php?id=<?= $id_game; ?>" enctype="multipart/form-data">
    <label>Nama Game:</label><br>
    <input type="text" name="nama_game" value="<?= htmlspecialchars($row['nama_game']); ?>" required><br><br>
    
    <label>Developer:</label><br>
    <input type="text" name="developer" value="<?= htmlspecialchars($row['developer']); ?>" required><br><br>
    
    <label>Batas Usia:</label><br>
    <input type="text" name="batas_usia" value="<?= htmlspecialchars($row['batas_usia']); ?>" required><br><br>
    
    <label>Link Game:</label><br>
    <input type="url" name="link_game" value="<?= htmlspecialchars($row['link_game']); ?>"><br><br>
    
    <label>Deskripsi:</label><br>
    <textarea name="deskripsi" required><?= htmlspecialchars($row['deskripsi']); ?></textarea><br><br>
    
    <label>Link Mabar:</label><br>
    <input type="url" name="link_mabar" value="<?= htmlspecialchars($row['link_mabar']); ?>"><br><br>
    
    <label>Link Komunitas:</label><br>
    <input type="url" name="link_komunitas" value="<?= htmlspecialchars($row['link_komunitas']); ?>"><br><br>
    
    <label>Foto Game:</label><br>
    <input type="file" name="foto_game"><br><br>
    <?php if (file_exists($row['foto_game'])): ?>
        <img src="<?= htmlspecialchars($row['foto_game']); ?>" width="100">
    <?php else: ?>
        <img src="uploads/default.jpg" width="100"> <!-- Gambar default jika tidak ada -->
    <?php endif; ?>

    <button type="submit">Simpan Perubahan</button>
</form>
