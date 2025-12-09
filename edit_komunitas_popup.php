<?php
include 'db_connection.php';
$id_komunitas = $_GET['id'];

$sql = "SELECT k.*, g.nama_game 
        FROM komunitas k 
        JOIN game g ON k.id_game = g.id_game 
        WHERE k.id_komunitas = $id_komunitas";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Get all games for dropdown
$games_sql = "SELECT id_game, nama_game FROM game ORDER BY nama_game";
$games_result = $conn->query($games_sql);
?>

<form action="edit_komunitas.php?id=<?= $id_komunitas ?>" method="post" enctype="multipart/form-data">
    <label>Nama Komunitas:</label>
    <input type="text" name="nama_komunitas" value="<?= htmlspecialchars($row['nama_komunitas']) ?>" required><br><br>

    <label>Deskripsi:</label>
    <textarea name="deskripsi" required><?= htmlspecialchars($row['deskripsi']) ?></textarea><br><br>

    <label>Link Komunitas:</label>
    <input type="text" name="link_komunitas" value="<?= htmlspecialchars($row['link_komunitas']) ?>" required><br><br>

    <label>Game:</label>
    <select name="id_game" required>
        <?php while($game = $games_result->fetch_assoc()): ?>
            <option value="<?= $game['id_game'] ?>" <?= ($game['id_game'] == $row['id_game']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($game['nama_game']) ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Foto Komunitas:</label><br>
    <input type="file" name="foto_komunitas"><br>
    <?php if (!empty($row['foto_komunitas']) && file_exists($row['foto_komunitas'])): ?>
        <img src="<?= htmlspecialchars($row['foto_komunitas']) ?>" width="100" alt="Current community image"><br>
        <small>Current image will be kept if no new image is uploaded</small>
    <?php endif; ?>
    <br><br>

    <button type="submit">Simpan</button>
</form>