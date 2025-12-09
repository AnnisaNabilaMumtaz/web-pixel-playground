<?php
include 'db_connection.php';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_komunitas = $_POST['nama_komunitas'];
    $deskripsi = $_POST['deskripsi'];
    $link_komunitas = $_POST['link_komunitas'];
    $id_game = $_POST['id_game'];

    // Cek jika ada upload foto baru
    if (!empty($_FILES['foto_komunitas']['name'])) {
        $foto_name = $_FILES['foto_komunitas']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . $foto_name;
        
        if (move_uploaded_file($_FILES['foto_komunitas']['tmp_name'], $target_file)) {
            // Update dengan foto baru
            $sql_komunitas = "UPDATE komunitas SET 
                    nama_komunitas=?, 
                    deskripsi=?, 
                    link_komunitas=?, 
                    id_game=?, 
                    foto_komunitas=? 
                    WHERE id_komunitas=?";
            
            $stmt = $conn->prepare($sql_komunitas);
            $stmt->bind_param("sssssi", $nama_komunitas, $deskripsi, $link_komunitas, $id_game, $target_file, $id);
        }
    } else {
        // Update tanpa mengubah foto
        $sql_komunitas = "UPDATE komunitas SET 
                nama_komunitas=?, 
                deskripsi=?, 
                link_komunitas=?, 
                id_game=? 
                WHERE id_komunitas=?";
        
        $stmt = $conn->prepare($sql_komunitas);
        $stmt->bind_param("ssssi", $nama_komunitas, $deskripsi, $link_komunitas, $id_game, $id);
    }

    if ($stmt->execute()) {
        // Update link komunitas di tabel game
        $sql_game = "UPDATE game SET link_komunitas=? WHERE id_game=?";
        $stmt_game = $conn->prepare($sql_game);
        $stmt_game->bind_param("si", $link_komunitas, $id_game);
        $stmt_game->execute();
        
        header("Location: admin_komunitas_page.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Komunitas</title>
    <link rel="stylesheet" href="editkomunitas.css"> <!-- Link ke file CSS -->
</head>
<body>
    <div class="container">
        <h1>Edit Komunitas</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="nama_komunitas">Nama Komunitas:</label>
            <input type="text" id="nama_komunitas" name="nama_komunitas" value="<?= $row['nama_komunitas'] ?>" required>
            
            <label for="deskripsi">Deskripsi:</label>
            <textarea id="deskripsi" name="deskripsi" required><?= $row['deskripsi'] ?></textarea>
            
            <label for="link_komunitas">Link Komunitas:</label>
            <input type="text" id="link_komunitas" name="link_komunitas" value="<?= $row['link_komunitas'] ?>" required>
            
            <label for="id_game">ID Game:</label>
            <input type="number" id="id_game" name="id_game" value="<?= $row['id_game'] ?>" required>
            
            <label>Foto Komunitas:</label><br>
            <input type="file" name="foto_komunitas"><br><br>
            <?php if (file_exists($row['foto_komunitas'])): ?>
                <img src="<?= htmlspecialchars($row['foto_komunitas']); ?>" width="100">
            <?php else: ?>
                <img src="uploads/default.jpg" width="100"> <!-- Gambar default jika tidak ada -->
            <?php endif; ?>
            
            <button type="submit">Simpan</button>
        </form>
    </div>
</body>
</html>