<?php
include 'db_connection.php';

// Periksa apakah 'id' ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Parameter 'id' tidak ditemukan di URL.");
}

$id_game = intval($_GET['id']); // Konversi id menjadi integer untuk keamanan
$sql = "SELECT * FROM game WHERE id_game = $id_game";
$result = $conn->query($sql);

// Periksa apakah query berhasil dan data ditemukan
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("Error: Data game tidak ditemukan.");
}
?>

<h1>Detail Game</h1>
<p><b>Nama Game:</b> <?= htmlspecialchars($row['nama_game']); ?></p>
<p><b>Developer:</b> <?= htmlspecialchars($row['developer']); ?></p>
<p><b>Batas Usia:</b> <?= htmlspecialchars($row['batas_usia']); ?></p>
<p><b>Link Game:</b> <a href="<?= htmlspecialchars($row['link_game']); ?>" target="_blank"><?= htmlspecialchars($row['link_game']); ?></a></p>
<p><b>Deskripsi:</b> <?= htmlspecialchars($row['deskripsi']); ?></p>
<p><b>Link Mabar:</b> <a href="<?= htmlspecialchars($row['link_mabar']); ?>" target="_blank"><?= htmlspecialchars($row['link_mabar']); ?></a></p>
<p><b>Link Komunitas:</b> <a href="<?= htmlspecialchars($row['link_komunitas']); ?>" target="_blank"><?= htmlspecialchars($row['link_komunitas']); ?></a></p>

<p><b>Foto:</b><br>
        <?php if (file_exists($row['foto_game'])): ?>
            <img src="<?= htmlspecialchars($row['foto_game']); ?>" width="100">
        <?php else: ?>
            <img src="uploads/default.jpg" width="100"> <!-- Gambar default jika tidak ada -->
        <?php endif; ?>
</p>

<p><b>Dibuat Pada:</b> <?= htmlspecialchars($row['created_at']); ?></p>
