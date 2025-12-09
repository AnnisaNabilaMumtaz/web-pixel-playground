<?php
include 'db_connection.php';

// Periksa apakah 'id' ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Parameter 'id' tidak ditemukan di URL.");
}

$id_komunitas = intval($_GET['id']); // Konversi id menjadi integer untuk keamanan
$sql = "SELECT * FROM komunitas WHERE id_komunitas = $id_komunitas";
$result = $conn->query($sql);

// Periksa apakah query berhasil dan data ditemukan
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("Error: Data komunitas tidak ditemukan.");
}
?>

<h1>Detail Komunitas</h1>
    <p><b>Nama Komunitas:</b> <?= htmlspecialchars($row['nama_komunitas']); ?></p>
    <p><b>Deskripsi:</b> <?= htmlspecialchars($row['deskripsi']); ?></p>
    <p><b>Link Komunitas:</b> <a href="<?= htmlspecialchars($row['link_komunitas']); ?>" target="_blank"><?= htmlspecialchars($row['link_komunitas']); ?></a></p>
    <p><b>ID Game:</b> <?= htmlspecialchars($row['id_game']); ?></p>
    
    <p><b>Foto:</b><br>
        <?php if (file_exists($row['foto_komunitas'])): ?>
            <img src="<?= htmlspecialchars($row['foto_komunitas']); ?>" width="100">
        <?php else: ?>
            <img src="uploads/default.jpg" width="100"> <!-- Gambar default jika tidak ada -->
        <?php endif; ?>
    </p>

    <p><b>Dibuat Pada:</b> <?= htmlspecialchars($row['created_at']); ?></p>
